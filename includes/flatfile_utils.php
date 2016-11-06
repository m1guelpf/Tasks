<?php
    /*!
     * FlatFile - Flat File Data Storage
     * @version		v 1.0
     * @date		October 2016
     *
     * Copyright 2016 Miguel Piedrafita
     * Released under the Mozilla license 2.0
     */

    define('FLOAT_COL', 'float');
    define('INT_COL', 'int');
    define('STRING_COL', 'string');
    define('DATE_COL', 'date');

    class Column
    {
        public function Column($index, $type)
        {
            $this->index = $index;
            $this->type = $type;
        }
    }

    class JoinColumn
    {
        public function JoinColumn($index, $tablename, $columnname)
        {
            $this->index = $index;
            $this->tablename = $tablename;
            $this->columnname = $columnname;
        }
    }

    class TableUtils
    {
        /*
         * Finds JoinColumns in an array of tables, and adds 'type' fields by looking up the columns
         */
        public function resolveJoins(&$tables)
        {
            foreach ($tables as $tablename => $discard) {
                $tabledef = &$tables[$tablename];
                foreach ($tabledef as $colname => $discard) {
                    $coldef = &$tabledef[$colname];
                    if (is_a($coldef, 'JoinColumn') || is_subclass_of($coldef, 'JoinColumn')) {
                        self::resolveColumnJoin($coldef, $tables);
                    }
                }
            }
        }

        // Access private
        public function resolveColumnJoin(&$columndef, &$tables)
        {
            $columndef->type = $tables[$columndef->tablename][$columndef->columnname]->type;
        }

        public function createDefines(&$tables)
        {
            foreach ($tables as $tablename => $discard) {
                $tabledef = &$tables[$tablename];
                foreach ($tabledef as $colname => $discard) {
                    $coldef = &$tabledef[$colname];
                    define(strtoupper($tablename).'_'.$colname, $coldef->index);
                }
            }
        }

        /*
         * Creates a "row schema" for a given table definition.
         */
        public function createRowSchema(&$tabledef)
        {
            $row_schema = [];
            foreach ($tabledef as $colname => $coldef) {
                $row_schema[$coldef->index] = $coldef->type;
            }

            return $row_schema;
        }
    }
