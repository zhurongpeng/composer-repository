<?php
namespace App\Validator;

use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class Validator
{
    private $factory;
    private $validator;
    private $translator;

    public function __construct()
    {
        $translationPath = __DIR__.'/lang';
        $translationLocale = 'en';
        $translationFileLoader = new FileLoader(new Filesystem, $translationPath);

        $this->translator = new Translator($translationFileLoader, $translationLocale);
    }

    private function instance()
    {
        $this->factory = new Factory($this->translator);
    }

    public function make($data=[], $rules=[], $messages=[], $attributes=[])
    {
        $this->instance();

        $messages = $this->setMessages();

        $this->validator = $this->factory->make($data, $rules, $messages);

        return $this->validator->fails();
    }

    public function getMessage()
    {
        $key = $this->validator->messages()->keys()[0];

        return $key.$this->validator->messages()->first();
    }

    public function setMessages()
    {
        return [
            'required' => '参数必传',
            'min' => '参数内容未达到最小长度',
            'max' => '参数内容超出最大长度',
            'integer' => '参数必须为数字',
        ];
    }
}

