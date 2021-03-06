<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013-2014 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Controller\Back;

use ScContent\Controller\AbstractBack,
    ScContent\Service\Back\ContentListProvider,
    ScContent\Service\Back\ContentListOptionsProvider as OptionsProvider,
    ScContent\Form\Back\ContentSearchForm,
    //
    Zend\View\Model\ViewModel,
    Zend\Http\Response;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
class ContentManagerController extends AbstractBack
{
    /**
     * @var \ScContent\Service\Back\ContentListOptionsProvider
     */
    protected $optionsProvider;

    /**
     * @var \ScContent\Service\ContentListProvider
     */
    protected $contentListsProvider;

    /**
     * @var \ScContent\Form\Back\ContentSearchForm
     */
    protected $searchForm;

    /**
     * Show content list.
     *
     * @return \Zend\Stdlib\ResponseInterface|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $optionsProvider = $this->getOptionsProvider();
        if ($this->getRequest()->isPost()) {
            $event = $this->request->getPost('suboperation');
            if (! empty($event)) {
                $events = $this->getEventManager();
                $params = $this->request->getPost();
                $result = $events->trigger($event, $this, $params);
                if ($result->last() instanceof Response) {
                    return $result->last();
                }
            }
        }
        $lists = $this->getContentListProvider();

        $view = new ViewModel([
            'optionsProvider' => $optionsProvider,
            'contentListsProvider' => $lists
        ]);

        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $view->messages = $flashMessenger->getMessages();
        }

        return $view;
    }

    /**
     * Show search options.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function searchAction()
    {
        $optionsProvider = $this->getOptionsProvider();
        $pane = $this->params()->fromRoute('pane');
        if (! $pane || ! $optionsProvider->hasIdentifier($pane)) {
            $pane = 'first';
        }

        $options = $optionsProvider->getOptions($pane, 'search');

        $search = $optionsProvider->getSearchProxy($pane);
        $form = $this->getSearchForm();
        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'sc-admin/content-search',
                ['pane' => $pane]
            )
        );
        $form->bind($search);
        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();
            if (array_key_exists('clean', $post)) {
                $search->clean();
            } else {
                $form->setData($post);
            }
            if ($form->isValid()) {
                $options->setSearchOptions(
                    $form->getData(ContentSearchForm::VALUES_AS_ARRAY)
                );
                $optionsProvider->save($options->getName());
            }
        }
        $list = $this->getContentListProvider()->getList($pane);

        return new ViewModel([
            'options' => $options,
            'list' => $list,
            'pane' => $pane,
            'form' => $form,
        ]);
    }

    /**
     * @param  \ScContent\Service\Back\ContentListOptionsProvider $optionsProvider
     * @return void
     */
    public function setOptionsProvider(OptionsProvider $optionsProvider)
    {
        $this->optionsProvider = $optionsProvider;
    }

    /**
     * @return \ScContent\Service\Back\ContentListOptionsProvider
     */
    public function getOptionsProvider()
    {
        if (! $this->optionsProvider instanceof OptionsProvider) {
            $serviceLocator = $this->getServiceLocator();
            $this->optionsProvider = $serviceLocator->get(
                'ScService.Back.ContentListOptionsProvider'
            );
        }
        return $this->optionsProvider;
    }

    /**
     * @param  \ScContent\Service\Back\ContentListProvider $contentListProvider
     * @return void
     */
    public function setContentListProvider(
        ContentListProvider $contentListProvider
    ) {
        $this->contentListsProvider = $contentListProvider;
    }

    /**
     * @return \ScContent\Service\Back\ContentListProvider
     */
    public function getContentListProvider()
    {
        if (! $this->contentListsProvider instanceof ContentListProvider) {
            $serviceLocator = $this->getServiceLocator();
            $this->contentListsProvider = $serviceLocator->get(
                'ScService.Back.ContentListProvider'
            );
        }
        return $this->contentListsProvider;
    }

    /**
     * @param  \ScContent\Form\Back\ContentSearchForm $form
     * @return void
     */
    public function setSearchForm(ContentSearchForm $form)
    {
        $this->searchForm = $form;
    }

    /**
     * @return \ScContent\Form\Back\ContentSearchForm
     */
    public function getSearchForm()
    {
        if (! $this->searchForm instanceof ContentSearchForm) {
            $formElementManager = $this->getServiceLocator()->get(
                'FormElementManager'
            );
            $this->searchForm = $formElementManager->get(
                'ScForm.Back.ContentSearch'
            );
        }
        return $this->searchForm;
    }
}
