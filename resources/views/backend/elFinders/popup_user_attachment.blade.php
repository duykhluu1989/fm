<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | User Attachment</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/elfinder/css/elfinder.min.css') }}">
</head>
<body>
<div id="elfinder"></div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('packages/elfinder/js/elfinder.min.js') }}"></script>
<script type="text/javascript">
    $().ready(function() {
        var elf = $('#elfinder').elfinder({
            customData: {
                _token: '{{ csrf_token() }}'
            },
            url: '{{ action('Backend\ElFinderController@popupConnectorUserAttachment', ['id' => $id]) }}',
            width: 1170,
            height: 550,
            resizable: false
        }).elfinder('instance');
    });
</script>
</body>
</html>
