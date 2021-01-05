<?php

namespace Militar\Model;

use RuntimeException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

//use Zend\Db\Sql\Select;

class MilitarTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select(order('nip DESC'));
    }

    private function fetchPaginatedResults() {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the miliatr entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Militar());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
                // our configured select object:
                $select,
                // the adapter to run it against:
                $this->tableGateway->getAdapter(),
                // the result set to hydrate:
                $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getMilitar($nip) {
        $nip = (int) $nip;
        $rowset = $this->tableGateway->select(['nip' => $nip]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                                    'Could not find row with identifier %d',
                                    $nip
            ));
        }

        return $row;
    }

    public function saveMilitar(Militar $militar) {
        $data = [
            'nip' => $militar->nip,
            'posto' => $militar->posto,
            'nome' => $militar->nome,
        ];
        $this->tableGateway->insert($data);
        return;
    }

    public function updateMilitar(Militar $militar) {
        $data = [
            'nip' => $militar->nip,
            'posto' => $militar->posto,
            'nome' => $militar->nome,
        ];

        try {
            $this->getMilitar($militar->nip);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                                    'Cannot update militar with identifier %d; does not exist',
                                    $militar->nip
            ));
        }

        $this->tableGateway->update($data, ['nip' => $militar->nip]);
    }

    public function deleteMilitar($nip) {
        $this->tableGateway->delete(['nip' => (int) $nip]);
    }

}
