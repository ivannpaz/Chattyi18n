<?php

namespace App\Controllers;

/**
*
*/
class HomeController extends BaseController
{

    /**
     * Home
     *
     * @return Response
     */
    public function index()
    {
        return $this->app->render('home/index.html.twig', array());
    }

    /**
     * Generate Chat session and display UI
     *
     * @return Response
     */
    public function chat()
    {
        $token = $this->app['session']->get('session_token', false);

        if (!$token) {
            $token = uniqid(16);
            $this->app['session']->set('session_token', $token);
            $this->app['predis']->set("chat_{$token}", $token);
            $this->app['predis']->expire("chat_{$token}", 10 * 60);
        }

        return $this->app->render('home/chat.html.twig', array(
            'token' => $token,
        ));
    }

    /**
     * Clear chat session and request a new token
     */
    public function clear()
    {
        $this->app['session']->clear();

        return $this->app->redirect('/chat');
    }

}
