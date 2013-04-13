<?php

/**
 * Description of Fetcher
 *
 * @author micah
 */
class Fetcher
{
    protected $wheres, $index, $sql, $callBack, $orderBy, $limit, $selects = array();

    public function __construct()
    {
        $this->wheres = array();
    }

    /**
     * Add a collection of columns to the SQL query
     * 
     * @param array $selects Which columns should be added to the query
     * @param string $prefix optional text to help isolate the columns
     * 
     * @return \Fetcher
     */
    public function addSelects(array $selects, $prefix = null)
    {
        foreach ($selects as $select) {
            if (!is_null($prefix)) {
                $select = $prefix . '.' . $select . ' AS '.$prefix.'_' . $select; 
            }
            $this->selects[] = $select;
        }
        return $this;
    }
    
    /**
     * Setter for the order by property
     * 
     * @param string $orderBy Takes in an order by string, 
     *     if it is valid, assign it to the property
     * @return \Fetcher
     */
    public function setOrderBy($orderBy)
    {
        $pattern   = '%^([\w]+)(?:\s+(ASC|DESC))?$%i';
        $order_bys = array();
        $has_error = false;
        
        // Loop through each possible order by clause
        foreach (explode(',', $orderBy) as $entry) {
            // Check to see if the clause is a valid one for ORDER BY
            if (preg_match($pattern, trim($entry), $matches)) {
                // If there is a direction supplied, append it to the temp ary
                $order_bys[] = !empty($matches[2]) 
                    ? $matches[1] . ' ' . strtoupper($matches[2]) 
                    : $matches[1];
            } else {
                // If the entry does not pass our test,  flag for errors
                $has_error = true;
                // Bail out, no sense continuing
                break;
            }
        }
        // If there have been some matches and there weren't any errors,
        // assign the new value
        if (count($order_bys) > 0 && $has_error == false) {
            $this->orderBy = implode(', ', $order_bys);
        } else {
            $this->orderBy = null;
        }
        // Chainability
        return $this;
    }

    /**
     * Return the current value for the orderBy property
     * 
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Setter for limit
     * 
     * @param string $limit New value for the limit property
     * 
     * @return \Fetcher
     */
    public function setLimit($limit)
    {
        // Check to make sure that the new limit is of propert form
        if (preg_match('%^\s*(\d+)\s*(?:,\s*(\d+))?$%', $limit, $match)) {
            $this->limit = array_key_exists(2, $match) 
                ? $match[1] . ', ' . $match[2] // combine both indexes w/comma 
                : $match[1];                  //  just use the one index
        }
        // Chainability
        return $this;
    }

    /**
     * Simple getter for the limit property
     * 
     * @return string
     */
    public function getLimit()
    {
        return $this->limit;
    }
/*
    public function getWheres()
    {
        return $this->wheres;
    }

    public function setWheres($wheres)
    {
        $this->wheres = $wheres;
        return $this;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function setSql($sql)
    {
        $this->sql = $sql;
        return $this;
    }

    public function getCallBack()
    {
        return $this->callBack;
    }

    public function setCallBack($callBack)
    {
        $this->callBack = $callBack;
        return $this;
    }

    public function addWhere($column, $value, $sign = '=')
    {
        $this->wheres[] = array('column' => $column, 'value' => $value, 'sign' => $sign);
        return $this;
    }

    public function setIndex($newIndex)
    {
        $this->index = $newIndex;
        return $this;
    }

    public function formatWheres(&$params)
    {
        $ret = '';
        if (count($this->wheres) == 0) {
            return '';
        }

        $ret = ' WHERE ';
        foreach ($this->wheres as $index => $where) {
            if ($index > 0) {
                $ret.= ' AND ';
            }
            $ret.= sprintf('%s %s ?', $where['column'], $where['sign']);
            $params[] = $where['value'];
        }

        return $ret;
    }

    public function formatLimit()
    {
        if (is_null($this->limit)) {
            return '';
        }
        return ' LIMIT ' . $this->limit;
    }

    public function formatIndex()
    {
        if (is_null($this->index)) {
            return '';
        }
        return ' LIMIT ' . $this->index . ', 1';
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function formatOrderBy()
    {
        if (!is_null($this->orderBy))
            return ' ORDER BY ' . $this->orderBy; return '';
    }

    public function fetch()
    {
        $dbh = DB::grab();
        $executeParams = array();
        $sql = $this->sql . ' ' . $this->formatWheres($executeParams) . $this->formatOrderBy() . ($this->index ? $this->formatIndex() : $this->formatLimit());

#       echo $sql; 
        $sth = $dbh->prepare($sql);
        $results = array();
        if ($sth->execute($executeParams)) {
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                if (is_callable($this->callBack)) {
                    $results[] = call_user_func($this->callBack, $row);
                } else {
                    $results[] = $tag;
                }
            }
        } else {
            print_r($sth->errorInfo());
        }

        if (!is_null($this->index) && count($results) == 1) {
            return $results[0];
        }
        return $results;
    }
*/
}
