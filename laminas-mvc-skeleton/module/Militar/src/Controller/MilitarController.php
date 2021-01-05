<?php

namespace Militar\Controller;

use Militar\Model\MilitarTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Militar\Form\MilitarForm;
use Militar\Model\Militar;

class MilitarController extends AbstractActionController {

    private $table;

    public function __construct(MilitarTable $table) {
        $this->table = $table;
    }

    public function indexAction() {
        // Grab the paginator from the AlbumTable:
        $paginator = $this->table->fetchAll(true);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(8);

        return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction() {
        $form = new MilitarForm();
        $form->get('submit')->setValue('Adicionar');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $militar = new Militar();
        $form->setInputFilter($militar->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        echo '<pre>';
        var_dump($form->getData());
        echo '</pre>';
        die();

        $militar->exchangeArray($form->getData());

        $this->table->saveMilitar($militar);
        return $this->redirect()->toRoute('militar');
    }

    public function editAction() {
        // Retrieve the militar with the specified nip. Doing so raises
        // an exception if the militar is not found, which should result
        // in redirecting to the landing page.


        $nip = (int) $this->params()->fromRoute('nip', 0); // pega o parametro da rota, se nao encontrar coloca 0

        try {
            $militar = $this->table->getMilitar($nip);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('militar', ['action' => 'index']);
        }

        $form = new MilitarForm();
        $form->bind($militar);
        $form->get('submit')->setAttribute('value', 'Salvar');

        $request = $this->getRequest();
        $viewData = ['nip' => $nip, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($militar->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->updateMilitar($militar);

        // Redirect to album list
        return $this->redirect()->toRoute('militar', ['action' => 'index']);
    }

    public function deleteAction() {
        $nip = (int) $this->params()->fromRoute('nip', 0);
        if (!$nip) {
            return $this->redirect()->toRoute('militar');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Não');

            if ($del == 'Sim') {
                $nip = (int) $request->getPost('nip');
                $this->table->deleteMilitar($nip);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('militar');
        }

        return [
            'nip' => $nip,
            'militar' => $this->table->getMilitar($nip),
        ];
    }

}
