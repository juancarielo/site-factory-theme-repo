<?php

namespace Drupal\endroid_qr_code\Response;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

/**
 * Response which is returned as the QR code.
 *
 * @package Drupal\endroid_qr_code\Response
 */
class QRImageResponse extends Response {

  /**
   * Recourse with generated image.
   *
   * @var resource
   */
  protected $image;

  /**
   * Data to be used.
   *
   * @var data
   */
  private $data;

  /**
   * Logo file.
   *
   * @var file
   */
  private $file;

  /**
   * Label.
   *
   * @var label
   */
  private $label;

  /**
   * Logo Size.
   *
   * @var logoSize
   */
  private $logoSize;

  /**
   * Logo margin.
   *
   * @var logoMargin
   */
  private $logoMargin;

  /**
   * PNG Writer Class.
   *
   * @var writer
   */
  private $writer;

  /**
   * {@inheritdoc}
   */
  public function __construct($content, $file, $label, $logoSize, $logoMargin, $status = 200, $headers = []) {
    parent::__construct(NULL, $status, $headers);
    $this->data = $content;
    $this->file= $file;
    $this->label = $label;
    $this->logoSize = (NULL !== $logoSize) ? (int) $logoSize : 600;
    $this->logoMargin = (NULL !== $logoMargin) ? (int) $logoMargin : 10;
    $this->writer =  new PngWriter();
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(Request $request): static {
    return parent::prepare($request);
  }

  /**
   * {@inheritdoc}
   */
  public function sendHeaders(): static {
    $this->headers->set('content-type', 'image/jpeg');

    return parent::sendHeaders();
  }

  /**
   * {@inheritdoc}
   */
  public function sendContent(): static {
    $this->generateQrCode($this->data);
    return parent::sendHeaders();
  }

  /**
   * Function generate QR code for the string or URL.
   *
   * @param string $string
   *   String to be converted to Qr Code.
   */
  private function generateQrCode(string $string = '') {
    $qrCode = new QrCode($string);
    $qrCode->setSize($this->logoSize);
    $qrCode->setMargin($this->logoMargin);
    $qrCode->setRoundBlockSizeMode(new RoundBlockSizeModeMargin());
    $qrCode->setEncoding(new Encoding('UTF-8'));
    $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh);
    $qrCode->setForegroundColor(new Color(0, 0, 0));
    $qrCode->setBackgroundColor(new Color(255, 255, 255));
    // Create generic logo.
    $logo =  Logo::create('');
    if ($this->file) {
      $logo = Logo::create($this->file)
      ->setResizeToWidth(50)
      ->setPunchoutBackground(true);
    }
    $label = Label::create('');
    if ($this->label) {
    // Create generic label
      $label = Label::create($this->label)
      ->setTextColor(new Color(255, 0, 0));
    }
    // $qrCode->setValidateResult(FALSE);
    $result = $this->writer->write($qrCode, $logo, $label);
    $response = new QrCodeResponse($result);
    if ($response->isOk()) {
      $im = imagecreatefromstring($response->getContent());
      ob_start();
      imagejpeg($im);
      imagedestroy($im);
    }
  }

}
