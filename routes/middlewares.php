<?php

function login_required(&$request, &$response) {
    if (!isset($_SESSION["user"])) {
        $response->code = 403;
        return false;
    }
    $request->user = $_SESSION["user"];
    unset($request->user['hash']);
    return true;
}
