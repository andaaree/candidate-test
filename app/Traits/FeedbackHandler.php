<?php
namespace App\Traits;

use ReflectionClass;
use stdClass;

trait FeedbackHandler
{

    /**
     * @param string $path $model->getShortName()
     * @param string $context (tambah/hapus)
     * @param string $custom custom message
     *
     * @return Object
     *
     */
    public function message($pt = null,$context = null,$custom = null)
    {
        $path = new ReflectionClass($pt);
        $path = $path->getShortName();
        $res = new stdClass;
        $res->status = "success";
        if (empty($context)) {
            $res->message = $custom;
        }else{
            $res->message = "$path telah di $context !";
        }
        return $res;
    }

    /**
     * @param string $path $model->getShortName()
     * @param \Exception $context
     *
     * @return Object
     *
     */
    public function err($pt = null,\Exception $context)
    {
        $path = new ReflectionClass($pt);
        $path = $path->getShortName();
        $res = new stdClass;
        $res->status = "error";
        $res->message = "$path error! \\n";
        $res->message .= $context->getMessage();
        return $res;
    }

    public function defaultNav($url)
    {
        $segments = array_values(array_filter(explode('/', trim($url, '/'))));
        $items = [
            ['label' => 'Dashboard', 'url' => '/dashboard']
        ];
        $currentUrl = '';
        
        foreach ($segments as $index => $segment) {
            $currentUrl .= '/' . $segment;
            $items[] = [
                'label' => ucfirst($segment),
                'url' => $currentUrl
            ];
        }

        return $items;
    }

}
