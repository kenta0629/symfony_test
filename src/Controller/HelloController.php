<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * リクエスト取得方法3
     *
     * @Route("/hello", name="hello")
     */
    public function index(Request $request): Response
    {
        if ($request->getMethod() == 'POST') {
            $input = $request->request->get('input');
            $msg = 'こんにちは、' . $input . 'さん！';
        } else {
            $msg = 'お名前は？';
        }

        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'message' => $msg
        ]);
    }

    // /**
    //  * リクエスト取得方法1
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => 'あなたのお名前'
    //     ]);
    // }

    // /**
    //  * リクエスト取得方法2
    //  *
    //  * @Route("/other", name="other")
    //  */
    // public function other(Request $request): Response
    // {
    //     $input = $request->request->get('input');
    //     $msg = 'こんにちは、' . $input . 'さん！';

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * リダイレクトでの変数受け渡し方法1
    //  *
    //  * @Route("/hello/{msg}", name="hello")
    //  */
    // public function index($msg='Hello!'): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //         'action' => 'index',
    //         'prev_action' => '(none)',
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * リダイレクトでの変数受け渡し方法２
    //  *
    //  * @Route("/hello/{action}/{msg}", name="other")
    //  */
    // public function other($action, $msg): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //         'action' => 'other',
    //         'prev_action' => $action,
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * JSONリダイレクト方法
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request, LoggerInterface $logger): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //     ]);

    //     $result = <<< EOM
    //         <html>
    //         <head><title>Hello</title></head>
    //         <body>
    //         <h1>Hello Symfony!</h1>
    //         <p>this is Symfony sample page.</p>
    //         </body>
    //         </html>
    //     EOM;

    //     $result = '<head><body>';
    //     $result .= '<h1>Subscribed Services</h1>';
    //     $result .= '<ol>';
    //     $arr = $this->getSubscribedServices();
    //     foreach ($arr as $key => $value) {
    //         $result .= '<li>' . $key . '</li>';
    //     }
    //     $result .= '</ol>';
    //     $result .= '</body></head>';

    //     $name = $request->get('name');
    //     $pass = $request->get('pass');

    //     $result = '<head><body>';
    //     $result .= '<h1>Parameter</h1>';
    //     $result .= '<p>name:' . $name . '</p>';
    //     $result .= '<p>pass:' . $pass . '</p>';
    //     $result .= '</body></head>';

    //     return new Response($result);

    //     $data = [
    //         'name' => [
    //             'first' => 'Taro',
    //             'second' => 'Yamada',
    //             'age' => 36,
    //             'mail' => 'taro@yamada.kun'
    //         ]
    //     ];

    //     $logger->info(serialize($data));

    //     return new JsonResponse($data);
    // }

    // /**
    //  * リダイレクト方法
    //  *
    //  * @Route("/other/{domain}", name="other")
    //  */
    // public function other(Request $request, $domain=''): Response
    // {
    //     if ($domain == '') {
    //         return $this->redirect('hello');
    //     } else {
    //         return new RedirectResponse("http://{$domain}.com");
    //     }
    // }
}
