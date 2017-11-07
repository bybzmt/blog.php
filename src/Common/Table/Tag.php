<?php
namespace Bybzmt\Blog\Dao;

class Tag extends Base
{
    public function getList(int $offset, int $length)
    {
        return Cache\IndexList::get('index', $offset, $length);
    }

    public function get($id)
    {
        return Cache\Tag::get($id);
    }

    public function add($name)
    {
        $data = array(
            'name' => $name,
            'status' => 0,
        );

        return Cache\Tag::add($data);
    }

    public function edit($id, $name)
    {
        $data = array(
            'name' => $name,
        );

        return Cache\Article::update($id, $data);
    }

    public function del($id)
    {
        $data = array(
            'status' => 2,
        );

        return Cache\Article::update($id, $data);
    }
}
