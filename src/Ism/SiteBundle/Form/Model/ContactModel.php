<?php
# src/SiteBundle/Form/Model/ContactModel.php

namespace Ism\SiteBundle\Form\Model;

class ContactModel
{
    /**
     * Email
     * @var string
     */
    private $email;

    /**
     * Subject
     * @var string
     */
    private $subject;

    /**
     * Content
     * @var string
     */
    private $content;

    public function getEmail() {
      return $this->email;
    }

    public function setEmail($email) {
      $this->email = $email;
    }

    public function getSubject() {
      return $this->subject;
    }

    public function setSubject($subject) {
      $this->subject = $subject;
    }

    public function getContent() {
      return $this->content;
    }

    public function setContent($content) {
      $this->content = $content;
    }
}
