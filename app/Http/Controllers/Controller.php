<?php

namespace App\Http\Controllers;

use Exception;

abstract class Controller
{
    protected function getDataJson()
    {
        $data = getDataJson();

        return $data;
    }

    protected function alert(string $type = 'success', string $msg = '')
    {
        session()->flash('alert', [
            'type' => $type,
            'message' => $msg
        ]);
        return $this;
    }

    protected function backWithAlert(string $type = 'success', string $msg = '')
    {
        return back()->with('alert', [
            'type' => $type,
            'message' => $msg
        ]);
    }

    protected function toWithAlert(string $routeName, ?array $params = [], ?string $type = 'success', string $msg = '')
    {
        return to_route($routeName, $params)->with('alert', [
            'type' => $type,
            'message' => $msg
        ]);
    }

    protected function alertJsonWitData(?array $data = [], string $type = 'success', string $msg = '', ?int $code = 200)
    {
        $response = array_merge($data, [
            'success' => $type === "success" ? true : false,
            'alert' => [
                'type' => $type,
                'msg' => $msg
            ]
        ]);
        return response()->json($response, $code);
    }

    protected function clearAngka($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    protected function formatTextToClass($text)
    {
        $text = str_replace('_', ' ', $text); // ubah _ jadi spasi
        $text = ucwords($text);               // kapital setiap kata
        return str_replace(' ', '', $text);   // hapus spasi
    }
}
