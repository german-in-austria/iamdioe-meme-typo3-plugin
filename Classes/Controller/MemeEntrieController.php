<?php
namespace HcbIamDioeMeme\HcbIamdioeMeme\Controller;

/***
 *
 * This file is part of the "iamDioe Meme" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * MemeEntrieController
 */
class MemeEntrieController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * memeEntrieRepository
     *
     * @var \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Repository\MemeEntrieRepository
     * @inject
     */
    protected $memeEntrieRepository = null;

    /**
     * action list
     *
     * @param HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie
     * @return void
     */
    public function listAction()
    {
        $memeEntries = $this->memeEntrieRepository->findAll();
        $this->view->assign('memeEntries', $memeEntries);
    }

    /**
     * action show
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie
     * @return void
     */
    public function showAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie)
    {
        $this->view->assign('memeEntrie', $memeEntrie);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {

    }

    /**
     * action create
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $newMemeEntrie
     * @return void
     */
    public function createAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $newMemeEntrie)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->memeEntrieRepository->add($newMemeEntrie);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie
     * @ignorevalidation $memeEntrie
     * @return void
     */
    public function editAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie)
    {
        $this->view->assign('memeEntrie', $memeEntrie);
    }

    /**
     * action update
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie
     * @return void
     */
    public function updateAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->memeEntrieRepository->update($memeEntrie);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie
     * @return void
     */
    public function deleteAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $memeEntrie)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->memeEntrieRepository->remove($memeEntrie);
        $this->redirect('list');
    }

    /**
     * action generator
     *
     * @param HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie
     * @return void
     */
    public function generatorAction()
    {
        $resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
        $memeimagesItems = array();
        $memeimagesItemUids = $this->settings['memeimages'];
        if(!empty($memeimagesItemUids)){
            $memeimagesItemUids = explode(',', $memeimagesItemUids);
            $arraySize = sizeof($memeimagesItemUids);
            for($i = 0; $i < $arraySize; $i++){
                $itemUid = $memeimagesItemUids[$i];
                $fileReference = $resourceFactory->getFileReferenceObject($itemUid);
                $fileArray = $fileReference->getProperties();
                array_push($memeimagesItems, $fileArray);
            }
        }
        $this->view->assign('pid', $this->configurationManager->getContentObject()->data['pages']);
        $this->view->assign('memeimagesItems', $memeimagesItems);
        $this->view->assign('teilnahmeText', $this->settings['teilnahme']);
        $this->view->assign('teilnahmeTextLen', strlen($this->settings['teilnahme']));
        $this->view->assign('datenschutzText', $this->settings['datenschutz']);
        $this->view->assign('danksagung', $this->settings['danksagung']);
    }

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * action generatorAjax
     *
     * @param \HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $newMemeEntrie
     * @return void
     */
    public function generatorAjaxAction(\HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie $newMemeEntrie)
    {
        $newMemeEntrie->setDatum(new \DateTime());
        $newMemeEntrie->setVotes(0);
        $newMemeEntrie->setFreigegeben(0);
        if ($newMemeEntrie->getDialekt() != 1) {
            $newMemeEntrie->setDialekt(0);
        }
        $resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
        $storage = $resourceFactory->getDefaultStorage();
        if (!$storage->hasFolder('memebilder')) {
            $storage->createFolder('memebilder');
        }
        $aImage = base64_decode(explode(',', $this->request->getArgument('image'))[1]);
        $aImageName = $newMemeEntrie->getPid().'_'.date_format(date_create(), 'YmdHis').'_'.substr(md5(time().uniqid()), -5).'.png';
        $uploadFolder = $storage->getFolder('memebilder');
        $tempFileName = tempnam(sys_get_temp_dir(), 'memeupload');
        $handle = fopen($tempFileName, "w");
        fwrite($handle, $aImage);
        fclose($handle);
        $file = $storage->addFile($tempFileName, $uploadFolder, $aImageName);
        if ($tempFileName && file_exists($tempFileName)) {
            unlink($tempFileName);
        }
        
        $fileReference = $this->objectManager->get('HcbIamDioeMeme\\HcbIamdioeMeme\\Domain\\Model\\FileReference');
        $fileReference->setFile($file);
        $newMemeEntrie->setBild($fileReference);

        $this->memeEntrieRepository->add($newMemeEntrie);
        $this->persistenceManager->persistAll();

        $this->view->assign('newMemeEntrie', $newMemeEntrie);
    }

    /**
     * action memelist
     *
     * @param HcbIamDioeMeme\HcbIamdioeMeme\Domain\Model\MemeEntrie
     * @return void
     */
    public function memelistAction()
    {
        $memeEntries = $this->memeEntrieRepository->getAllPublic();
        $this->view->assign('memeEntries', $memeEntries);
    }

}
