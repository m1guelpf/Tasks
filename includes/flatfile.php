<?php
    /*!
     * FlatFile - Flat File Data Storage
     * @version		v 1.0
     * @date		October 2016
     *
     * Copyright 2016 Miguel Piedrafita
     * Released under the Mozilla license 2.0
     */

    require_once 'flatfile_utils.php';

    define('DEFAULT_COMPARISON', '');
    define('STRING_COMPARISON', 'strcmp');
    define('INTEGER_COMPARISON', 'intcmp');
    define('NUMERIC_COMPARISON', 'numcmp');
    define('ASCENDING', 1);
    define('DESCENDING', -1);

    $comparison_type_for_col_type = [
        INT_COL    => INTEGER_COMPARISON,
        DATE_COL   => INTEGER_COMPARISON,
        STRING_COL => STRING_COMPARISON,
        FLOAT_COL  => NUMERIC_COMPARISON,
    ];

    function get_comparison_type_for_col_type($coltype)
    {
        global $comparison_type_for_col_type;

        return $comparison_type_for_col_type[$coltype];
    }

    /*
     * Provides simple but powerful flatfile database storage and retrieval
     *
     * @package flatfile
     */
    class Flatfile
    {
        public $tables;
        public $schemata;
        public $datadir;

        public function __construct()
        {
            $this->schemata = [];
        }

        /*
         * Get all rows from a table
         *
         * @param string $tablename	The table to get rows from
         * @return array The table as an array of rows, where each row is an array of columns
         */
        public function selectAll($tablename)
        {
            if (!isset($this->tables[$tablename])) {
                $this->loadTable($tablename);
            }

            return $this->tables[$tablename];
        }

        /*
         * Selects rows from a table that match the specified criteria
         *
         * This simulates the following SQL query:
         * <pre>
         *   SELECT LIMIT $limit * FROM  $tablename
         *   WHERE $whereclause
         *   ORDER BY $orderBy [ASC | DESC] [, $orderBy2 ...]
         * </pre>
         *
         * @param string $tablename	The table (file) to get the data from
         * @param object $whereClause 	Either a {@link WhereClause WhereClause} object to do selection of rows, or NULL to select all
         * @param mixed $limit  	Specifies limits for the rows returned:
         * @param mixed $orderBy	Either an {@link OrderBy} object or an array of them, defining the sorting that should be applied (if an array, then the first object in the array is the first key to sort on etc).  Use NULL for no sorting.
         * @return array The matching data, as an array of rows, where each row is an array of columns
         */
        public function selectWhere($tablename, $whereClause, $limit = -1, $orderBy = null)
        {
            if (!isset($this->tables[$tablename])) {
                $this->loadTable($tablename);
            }

            $table = $this->selectAll($tablename);

            $schema = $this->getSchema($tablename);
            if ($orderBy !== null) {
                usort($table, $this->getOrderByFunction($orderBy, $schema));
            }

            $results = [];
            $count = 0;

            if ($limit == -1) {
                $limit = [0, -1];
            } elseif (!is_array($limit)) {
                $limit = [0, $limit];
            }

            foreach ($table as $row) {
                if ($whereClause === null || $whereClause->testRow($row, $schema)) {
                    if ($count >= $limit[0]) {
                        $results[] = $row;
                    }
                    $count++;
                    if (($count >= $limit[1]) && ($limit[1] != -1)) {
                        break;
                    }
                }
            }

            return $results;
        }

        /*
         * Select a row using a unique ID
         *
         * @param string $tablename 	The table to get data from
         * @param string $idField	The index of the field containing the ID
         * @param string $id		The ID to search for
         * @return array 	The row of the table as an array
         */
        public function selectUnique($tablename, $idField, $id)
        {
            $result = $this->selectWhere($tablename, new SimpleWhereClause($idField, '=', $id));
            if (count($result) > 0) {
                return $result[0];
            } else {
                return [];
            }
        }

        /*
         * Get a lock for writing a file
         *
         * @access private
         */
        public function getLock($tablename)
        {
            ignore_user_abort(true);
            $fp = fopen($this->datadir.$tablename.'.lock', 'w');
            if (!flock($fp, LOCK_EX)) {
                // log error?
            }
            $this->loadTable($tablename);

            return $fp;
        }

        /*
         * Release a lock
         *
         * @access private
         */
        public function releaseLock($lockfp)
        {
            flock($lockfp, LOCK_UN);
            ignore_user_abort(false);
        }

        /*
         * Inserts a row with an automatically generated ID
         *
         * @param string $tablename	The table to insert data into
         * @param int $idField		The index of the field which is the ID field
         * @param array $newRow		The new row to add to the table
         * @return int		The newly assigned ID
         */
        public function insertWithAutoId($tablename, $idField, $newRow)
        {
            $lockfp = $this->getLock($tablename);
            $rows = $this->selectWhere(
                $tablename,
                null,
                1,
                    new OrderBy($idField, DESCENDING, INTEGER_COMPARISON)
            );
            if ($rows) {
                $newId = $rows[0][$idField] + 1;
            } else {
                $newId = 1;
            }
            $newRow[$idField] = $newId;
            $this->tables[$tablename][] = $newRow;
            $this->writeTable($tablename);
            $this->releaseLock($lockfp);

            return $newId;
        }

        /*
         * Inserts a row in a table
         *
         * @param string $tablename	The table to insert data into
         * @param array $newRow		The new row to add to the table
         */
        public function insert($tablename, $newRow)
        {
            $lockfp = $this->getLock($tablename);
            $this->tables[$tablename][] = $newRow;
            $this->writeTable($tablename);
            $this->releaseLock($lockfp);
        }

        /*
         * Updates an existing row using a unique ID
         *
         * @param string $tablename	The table to update
         * @param int $idField		The index of the field which is the ID field
         * @param array $updatedRow	The updated row to add to the table
         */
        public function updateRowById($tablename, $idField, $updatedRow)
        {
            $this->updateSetWhere(
                $tablename,
                $updatedRow,
                new SimpleWhereClause($idField, '=', $updatedRow[$idField])
            );
        }

        /*
         * Updates fields in a table for rows that match the provided criteria
         *
         * @param string $tablename	The table to update
         * @param array $newFields	A hashtable (with integer keys) of fields to update
         * @param WhereClause $whereClause	The criteria or NULL to update all rows
         */
        public function updateSetWhere($tablename, $newFields, $whereClause)
        {
            $schema = $this->getSchema($tablename);
            $lockfp = $this->getLock($tablename);
            for ($i = 0; $i < count($this->tables[$tablename]); $i++) {
                if ($whereClause === null || $whereClause->testRow($this->tables[$tablename][$i], $schema)) {
                    foreach ($newFields as $k => $v) {
                        $this->tables[$tablename][$i][$k] = $v;
                    }
                }
            }
            $this->writeTable($tablename);
            $this->releaseLock($lockfp);
            $this->loadTable($tablename);
        }

        /*
         * Deletes all rows in a table that match specified criteria
         *
         * @param string $tablename	The table to alter
         * @param object $whereClause.  {@link WhereClause WhereClause} object that will select
         * rows to be deleted.  All rows are deleted if $whereClause === NULL
         */
        public function deleteWhere($tablename, $whereClause)
        {
            $schema = $this->getSchema($tablename);
            $lockfp = $this->getLock($tablename);
            for ($i = count($this->tables[$tablename]) - 1; $i >= 0; $i--) {
                if ($whereClause === null || $whereClause->testRow($this->tables[$tablename][$i], $schema)) {
                    unset($this->tables[$tablename][$i]);
                }
            }
            $this->writeTable($tablename);
            $this->releaseLock($lockfp);
            $this->loadTable($tablename); // reset array indexes
        }

        /*
         * Delete all rows in a table
         *
         * @param string $tablename	The table to alter
         */
        public function deleteAll($tablename)
        {
            $this->deleteWhere($tablename, null);
        }

        /*
         * Gets a function that can be passed to usort to do the ORDER BY clause
         *
         * @param mixed $orderBy	Either an OrderBy object or an array of them
         * @return string function name
         */
        public function getOrderByFunction($orderBy, $rowSchema = null)
        {
            $orderer = new Orderer($orderBy, $rowSchema);

            return [&$orderer, 'compare'];
        }

        public function loadTable($tablename)
        {
            $filedata = @file($this->datadir.$tablename);
            $table = [];
            if (is_array($filedata)) {
                foreach ($filedata as $line) {
                    $line = rtrim($line, "\n");
                    $table[] = explode("\t", $line);
                }
            }
            $this->tables[$tablename] = $table;
        }

        public function writeTable($tablename)
        {
            $output = '';

            foreach ($this->tables[$tablename] as $row) {
                $keys = array_keys($row);
                rsort($keys, SORT_NUMERIC);
                $max = $keys[0];
                for ($i = 0; $i <= $max; $i++) {
                    if ($i > 0) {
                        $output .= "\t";
                    }
                    $data = (!isset($row[$i]) ? '' : $row[$i]);
                    $output .= str_replace(["\t", "\r", "\n"], [''], $data);
                }
                $output .= "\n";
            }
            $fp = @fopen($this->datadir.$tablename, 'w');
            fwrite($fp, $output, strlen($output));
            fclose($fp);
        }

        /*
         * Adds a schema definition to the DB for a specified regular expression
         *
         * @param string $fileregex   A regular expression used to match filenames
         * @param string $rowSchema  An array specifying the column types for data
         *                           files that match the regex, using constants defined in flatfile_utils.php
         */
        public function addSchema($fileregex, $rowSchema)
        {
            array_push($this->schemata, [$fileregex, $rowSchema]);
        }

        // Retrieves the schema for a given filename
        public function getSchema($filename)
        {
            foreach ($this->schemata as $rowSchemaPair) {
                $fileregex = $rowSchemaPair[0];
                if (preg_match($fileregex, $filename)) {
                    return $rowSchemaPair[1];
                }
            }
        }
    }

    /*
     * equivalent of strcmp for comparing integers, used internally for sorting and comparing
     */
    function intcmp($a, $b)
    {
        return (int) $a - (int) $b;
    }

    /*
     * equivalent of strcmp for comparing floats, used internally for sorting and comparing
     */
    function numcmp($a, $b)
    {
        return (float) $a - (float) $b;
    }

    /*
     * Used to test rows in a database table, like the WHERE clause in an SQL statement.
     *
     * @abstract
     * @package flatfile
     */
    class WhereClause
    {
        /*
         * Tests a table row object
         * @abstract
         * @param array $row   The row to test
         * @param array $rowSchema  An optional array specifying the schema of the table, using the INT_COL, STRING_COL etc constants
         * @return bool True if the $row passes the WhereClause
         * selection criteria, false otherwise
         */
        public function testRow($row, $rowSchema = null)
        {
        }
    }

    /*
     * Negates a where clause
     * @package flatfile
     */
    class NotWhere extends WhereClause
    {
        public $clause;

        /*
         * Contructs a new NotWhere object
         */
        public function __construct($whereclause)
        {
            $this->clause = $whereclause;
        }

        public function testRow($row, $rowSchema = null)
        {
            return !$this->clause->testRow($row, $rowSchema);
        }
    }

    /*
     * Implements a single WHERE clause that does simple comparisons of a field with a value.
     *
     * @package flatfile
     */
    class SimpleWhereClause extends WhereClause
    {
        public $field;
        public $operator;
        public $value;
        public $compare_type;

        /*
         * Creates a new {@link WhereClause WhereClause} object that does a comparison
         * of a field and a value.
         *
         * @param int $field        	The index (in the table row) of the field to test
         * @param string $operator  	The comparison operator, one of "=", "!=", "<", ">", "<=", ">="
         * @param mixed $value		The value to compare to.
         * @param string $compare_type	The comparison method to use - either
         * STRING_COMPARISON (default), NUMERIC COMPARISON or INTEGER_COMPARISON
         *
         */
        public function __construct($field, $operator, $value, $compare_type = DEFAULT_COMPARISON)
        {
            $this->field = $field;
            $this->operator = $operator;
            $this->value = $value;
            $this->compare_type = $compare_type;
        }

        public function testRow($tablerow, $rowSchema = null)
        {
            if ($this->field < 0) {
                return true;
            }

            $cmpfunc = $this->compare_type;
            if ($cmpfunc == DEFAULT_COMPARISON) {
                if ($rowSchema !== null) {
                    $cmpfunc = get_comparison_type_for_col_type($rowSchema[$this->field]);
                } else {
                    $cmpfunc = STRING_COMPARISON;
                }
            }

            if ($this->field >= count($tablerow)) {
                $dbval = '';
            } else {
                $dbval = $tablerow[$this->field];
            }
            $cmp = $cmpfunc($dbval, $this->value);
            if ($this->operator == '=') {
                return $cmp == 0;
            } elseif ($this->operator == '!=') {
                return $cmp != 0;
            } elseif ($this->operator == '>') {
                return $cmp > 0;
            } elseif ($this->operator == '<') {
                return $cmp < 0;
            } elseif ($this->operator == '<=') {
                return $cmp <= 0;
            } elseif ($this->operator == '>=') {
                return $cmp >= 0;
            }

            return false;
        }
    }

    /*
     * {@link WhereClause WhereClause} class to match a value from a list of items
     *
     * @package flatfile
     */
    class ListWhereClause extends WhereClause
    {
        public $field;
        public $list;
        public $compareAs;

        /*
         * Creates a new ListWhereClause object
         *
         * @param int $field		Field to match
         * @param array $list		List of items
         * @param string $compare_type Comparison type, string by default.
         */
        public function __construct($field, $list, $compare_type = DEFAULT_COMPARISON)
        {
            $this->list = $list;
            $this->field = (int) $field;
            $this->compareAs = $compare_type;
        }

        public function testRow($tablerow, $rowSchema = null)
        {
            $func = $this->compareAs;
            if ($func == DEFAULT_COMPARISON) {
                if ($rowSchema) {
                    $func = get_comparison_type_for_col_type($rowSchema[$this->field]);
                } else {
                    $func = STRING_COMPARISON;
                }
            }

            foreach ($this->list as $item) {
                if ($func($tablerow[$this->field], $item) == 0) {
                    return true;
                }
            }

            return false;
        }
    }

    /*
     * Abstract class that combines zero or more {@link WhereClause WhereClause} objects together.
     *
     * @package flatfile
     */
    class CompositeWhereClause extends WhereClause
    {
        public $clauses = [];

        /*
         * Add a {@link WhereClause WhereClause} to the list of clauses to be used for testing
         *
         * @param WhereClause $whereClause	The WhereClause object to add
         */
        public function add($whereClause)
        {
            $this->clauses[] = $whereClause;
        }
    }

    /*
     * {@link CompositeWhereClause CompositeWhereClause} that does an OR on all its child WhereClauses.
     *
     * @package flatfile
     */
    class OrWhereClause extends CompositeWhereClause
    {
        public function testRow($tablerow, $rowSchema = null)
        {
            foreach ($this->clauses as $clause) {
                if ($clause->testRow($tablerow, $rowSchema)) {
                    return true;
                }
            }

            return false;
        }

        /*
         * Creates a new OrWhereClause
         *
         * @param WhereClause $whereClause,... optional unlimited list of WhereClause objects to be added
         */
        public function __construct()
        {
            $this->clauses = func_get_args();
        }
    }

    /*
     * {@link CompositeWhereClause CompositeWhereClause} that does an AND on all its child WhereClauses.
     *
     * @package flatfile
     */
    class AndWhereClause extends CompositeWhereClause
    {
        public function testRow($tablerow, $rowSchema = null)
        {
            foreach ($this->clauses as $clause) {
                if (!$clause->testRow($tablerow, $rowSchema)) {
                    return false;
                }
            }

            return true;
        }

        /*
         * Creates a new AndWhereClause
         *
         * @param WhereClause $whereClause,... optional unlimited list of WhereClause objects to be added
         */
        public function __construct()
        {
            $this->clauses = func_get_args();
        }
    }

    /*
     * Stores information about an ORDER BY clause
     *
     * @package flatfile
     */
    class OrderBy
    {
        public $field;
        public $orderType;
        public $compareAs;

        /*
         * Creates a new OrderBy structure
         *
         * @param int $field	The index of the field to order by
         * @param int $orderType	ASCENDING or DESCENDING
         * @param int $compareAs	Comparison type: DEFAULT_COMPARISON, STRING_COMPARISON, INTEGER_COMPARISION,
         * or NUMERIC_COMPARISON, or the name of a user defined function that you want to use for doing the comparison.
         */
        public function __construct($field, $orderType, $compareAs = DEFAULT_COMPARISON)
        {
            $this->field = $field;
            $this->orderType = $orderType;
            $this->compareAs = $compareAs;
        }
    }

    /*
     * Implements the sorting defined by an array of OrderBy objects.  This class is used by {@link Flatfile::selectWhere()}
     *
     * @access private
     * @package flatfile
     */
    class Orderer
    {
        public $orderByList;

        /*
         * Creates new Orderer that will provide a sort function
         *
         * @param mixed $orderBy	An OrderBy object or an array of them
         * @param array $rowSchema	Option row schema
         */
        public function __construct($orderBy, $rowSchema = null)
        {
            if (!is_array($orderBy)) {
                $orderBy = [$orderBy];
            }
            if ($rowSchema) {
                foreach ($orderBy as $index => $discard) {
                    $item = &$orderBy[$index];
                    if ($item->compareAs == DEFAULT_COMPARISON) {
                        $item->compareAs = get_comparison_type_for_col_type($rowSchema[$item->field]);
                    }
                }
            }
            $this->orderByList = $orderBy;
        }

        /*
         * Compares two table rows using the comparisons defined by the OrderBy
         * objects.  This function is of the type that can be used passed to usort().
         */
        public function compare($row1, $row2)
        {
            return $this->compare_priv($row1, $row2, 0);
        }

        /*
         * @access private
         */
        public function compare_priv($row1, $row2, $index)
        {
            $orderBy = $this->orderByList[$index];
            $cmpfunc = $orderBy->compareAs;
            if ($cmpfunc == DEFAULT_COMPARISON) {
                $cmpfunc = STRING_COMPARISON;
            }
            $cmp = $orderBy->orderType * $cmpfunc($row1[$orderBy->field], $row2[$orderBy->field]);
            if ($cmp == 0) {
                if ($index == (count($this->orderByList) - 1)) {
                    return 0;
                } else {
                    return $this->compare_priv($row1, $row2, $index + 1);
                }
            } else {
                return $cmp;
            }
        }
    }
