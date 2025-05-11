$request->validate([
    'documento' => [
        'required',
        'file',
        'mimetypes:application/pdf,image/jpeg,image/png',
        'max:2048'
    ]
]);

$path = $request->documento->store(
    'sensitive/'.Str::random(10),
    'secure'
);