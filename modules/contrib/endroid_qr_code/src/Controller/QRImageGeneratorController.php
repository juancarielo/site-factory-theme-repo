<?php

namespace Drupal\endroid_qr_code\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\endroid_qr_code\Response\QRImageResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller which generates the image from defined settings.
 */
class QRImageGeneratorController extends ControllerBase {

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\File\FileSystemInterface definition.
   *
   * @var \Drupal\Core\File\FileSystemInterface;
   */
  protected $streamWrapperManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->request = $container->get('request_stack');
    $instance->configFactory = $container->get('config.factory');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->streamWrapperManager = $container->get('stream_wrapper_manager');
    return $instance;
  }

  /**
   * Main method that throw ImageResponse object to generate image.
   *
   * @return \Drupal\endroid_qr_code\Response\QRImageRespons
   *   Make a QR image in JPEG format.
   */
  public function image($content) {
    return new QRImageResponse($content, $this->getLogoFile(), $this->getlabel(), $this->getLogoSize(), $this->getLogoMargin());
  }

  /**
   * LogoWidth.
   *
   * @return int
   *   Will return the logo width.
   */
  public function getLogoWidth() {
    return $this->config('endroid_qr_code.settings')->get('logo_width');
  }

  /**
   * LogoSize.
   *
   * @return int
   *   Will return the logo size.
   */
  public function getLogoSize() {
    return $this->config('endroid_qr_code.settings')->get('set_size');
  }

  /**
   * LogoMargin.
   *
   * @return int
   *   Will return the logo margin.
   */
  public function getLogoMargin() {
    return $this->config('endroid_qr_code.settings')->get('set_margin');
  }

  /**
   * Logo.
   *
   * @return int
   *   Will return the logo.
   */
  public function getLogoFile() {
    $fid = $this->config('endroid_qr_code.settings')->get('logo_file');
    if ($fid) {
      $file = $this->entityTypeManager->getStorage('file')->load($fid);
      return $this->streamWrapperManager->getViaUri($file->getFileUri())->realpath();
    }
    return $fid;
  }

  /**
   * Label.
   *
   * @return string
   *   Will return the label.
   */
  public function getlabel() {
    return $this->config('endroid_qr_code.settings')->get('label');
  }

  /**
   * Will return the response for external url.
   *
   * @return \Drupal\endroid_qr_code\Response\QRImageRespons
   *   Will return the image response.
   */
  public function withUrl() {
    $externalUrl = $this->request->getCurrentRequest()->query->get('path');
    return new QRImageResponse($externalUrl, $this->getLogoFile(), $this->getlabel(), $this->getLogoSize(), $this->getLogoMargin());
  }

}
