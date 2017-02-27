<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginController extends Controller
{
    /**
     * @Template()
    */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        
        
        /*$mail = $this->get("myshop_admin.sending_mail");
        $mail->sendEmail("Someone has entered as admin!"); */

        return [
            'last_username' => $lastUsername,
            'error'         => $error,
        ];

         
    }
}