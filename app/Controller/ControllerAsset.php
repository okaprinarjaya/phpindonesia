<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Controller;

use Assetic\FilterManager;
use Assetic\Filter\LessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetCollection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * ControllerHome
 *
 * @author PHP Indonesia Dev
 */
class ControllerAsset extends ControllerBase 
{

	/**
	 * Handler untuk GET /asset/css/somecss.css
	 */
	public function actionCss() {
		// Ambil parameter dari request
		$id = $this->request->get('id', 'undefined');

		if ($id == 'main.css') {
			// Build CSS from LESS
			$am = new AssetManager();
			$fm = new FilterManager();
			$fm->set('less', new LessphpFilter());
			$factory = new AssetFactory(ASSET_PATH);
			$factory->setFilterManager($fm);
			$factory->setAssetManager($am);
			$css = $factory->createAsset(
				array(
					'less/bootstrap.less', // load every scss files from "/path/to/asset/directory/css/src/"
				), 
				array(
					'less', // filter through the filter manager's "scss" filter
				)
			);

			$file = $css->dump();
			$mime = 'text/css';
		} else {
			// @codeCoverageIgnoreStart
			// Validasi file
			$file = $this->validateAssetFile('css', $id);
			$mime = $file->getMimeType();
			// @codeCoverageIgnoreEnd
		}

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Handler untuk GET /asset/js/somejs.js
	 */
	public function actionJs() {
		// Ambil parameter dari request
		$id = $this->request->get('id', 'undefined');

		if ($id == 'app.js') {
			// Buatkan kompilasi Bootstrap JS
			$collection = new AssetCollection();
			$bootstrap = array(
				'js/bootstrap-alert.js',
				'js/bootstrap-modal.js',
				'js/bootstrap-dropdown.js',
				'js/bootstrap-scrollspy.js',
				'js/bootstrap-tab.js',
				'js/bootstrap-tooltip.js',
				'js/bootstrap-popover.js',
				'js/bootstrap-button.js',
				'js/bootstrap-collapse.js',
				'js/bootstrap-carousel.js',
				'js/bootstrap-typeahead.js',
				'js/bootstrap-affix.js'
			);

			foreach ($bootstrap as $js) {
				$collection->add(new FileAsset(ASSET_PATH . DIRECTORY_SEPARATOR . $js));
			}

			$file = $collection->dump();
			$mime = 'application/javascript';
		} else {
			// Validasi file
			$file = $this->validateAssetFile('js', $id);
			$mime = $file->getMimeType();
		}

		return $this->renderAsset($mime,$file);
	}
	
	/**
	 * Handler untuk GET /asset/img/someimage.png
	 */
	public function actionImg() {
		// Ambil parameter dari request
		$id = $this->request->get('id', 'undefined');

		// Validasi
		$file = $this->validateAssetFile('img', $id);
		$mime = $file->getMimeType();

		return $this->renderAsset($mime,$file);
	}

	/**
	 * Render method untuk Asset file
	 *
	 * @param string $mime MIME Type
	 * @param string $asset 
	 *
	 * @return Response
	 */
	protected function renderAsset($mime, $asset)
	{
		// Default cache adalah 5 menit
		$age = 60*5;

		if ($asset instanceof File) {
			$content = file_get_contents($asset);
			$lastModified = new \DateTime(date('Y-m-d\TH:i:sP',$asset->getMTime()));
		} else {
			$content = $asset;
			$lastModified = new \DateTime();
		}

		// Prepare asset response
		$assetResponse = $this->render($content, 200, array('Content-Type' => $mime));
		$assetResponse->setLastModified($lastModified);
		$assetResponse->setMaxAge($age);

		return $assetResponse;
	}

	/**
	 * Validasi ID dan existensi file
	 *
	 * @param  string $type [js|css|img]
	 * @param  string $fileName Nama file
	 *
	 * @return string $file Path
	 *
	 * @return InvalidArgumentException kalau file tidak ditemukan
	 */
	protected function validateAssetFile($type, $fileName) {
		// Dapatkan path dari file
		return new File(ASSET_PATH . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $fileName, true);
	}

}