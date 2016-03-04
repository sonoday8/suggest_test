<?php
class Trie {
    var $trie = array();
    var $s;
    function __construct($s=NULL){
        $this->s = $s;
    }

    function add($s, $i=1, $l=NULL){
        if (!$i) {
            throw new Exception('Invalid argument');
        }
        if (!$l) {
            $l = strlen($s);
            if (!$l)
                throw new Exception('Invalid argument');
        }
        if ($i == $l) {
            if (!array_key_exists($s, $this->trie)) {
                $this->trie[$s] = new Trie($s);
            } else {
                $this->trie[$s]->s = $s;
            }
            return TRUE;
        }

        $c = substr($s, 0, $i);
        if (!array_key_exists($c, $this->trie))
            $this->trie[$c] = new Trie();

        return $this->trie[$c]->add($s, $i+1, $l);
    }

    function search($s, $i=1, $l=NULL){
        if (! $l) {
            $l = strlen($s);
            if (! $l)
                throw new Exception('Invalid argument');
        }
        if ($i == $l) {
            if (array_key_exists($s, $this->trie))
                return $this->trie[$s]->getAll();

            return FALSE;
        }

        $c = substr($s, 0, $i);
        if (!array_key_exists($c, $this->trie))
            return FALSE;

        return $this->trie[$c]->search($s, $i+1, $l);
    }

    function getAll() {
        $ret = array();

        if ($this->s)
            $ret[] = $this->s;

        foreach ($this->trie as $k => $v)
            $ret = array_merge($ret, $v->getAll());

        return $ret;
    }
}
?>
