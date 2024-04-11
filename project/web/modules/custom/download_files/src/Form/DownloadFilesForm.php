<?php

declare(strict_types=1);

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Provides a Download files form.
 */
final class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'download_files';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['file'] = [
      '#type' => 'select',
      '#title' => $this->t('Select element'),
      '#options' => $this->getFiles(),
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  public function getFiles() {
    $results = \Drupal::database()
      ->select('file_managed', 'f')
      ->fields('f', ['filename', 'uri'])
      ->condition('f.status', 1)
      ->execute()
      ->fetchAll();

      $files = [];
      foreach ($results as $file) {
        $files[$file->uri] = $file->filename;
      }

      return $files;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $uri = $form_state->getValue('file');
    $response = new BinaryFileResponse($uri);
    $response->setContentDisposition('attachment');
    $form_state->setResponse($response);
    $this->messenger()->addStatus($this->t('The message has been sent.'));
  }

}
