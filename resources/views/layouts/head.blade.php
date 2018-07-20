<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta name="description" content="">
<meta name="author" content="">
<title>Time Monitoring</title>

<!-- Bootstrap core CSS -->
<link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Custom fonts for this template -->
<link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

<!-- Custom styles for this template -->
<link href="{{ asset('css/css/sb-admin.css') }}" rel="stylesheet">

<link href="{{ asset('css/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">


<script>
    function ElementWriteText(elId,text) {
        var obj = document.getElementById(elId);
        var txt = document.createTextNode(text);
        document.getElementById(elId).innerHTML = "";
        obj.appendChild(txt);
    }


    function SetValue(id, val)
    {
        $('#' + id).attr('value', val);
    }

    function SetAttribute(id, attr, val)
    {
        $('#' + id).attr(attr, val);
    }

    function RemoveElement(id)
    {
        $('#' + id).remove();
    }

    function showModalRemarks(){
        $('#ModalRemarks').modal('show');
    }

    function HideElement(id) {
        $('#' + id).hide();
    }

    function ShowElement(id) {
        $('#' + id).show();
    }

    function CheckElement(id){
        $("#table_wrapper tr td:nth-child(1) input[type=checkbox]").prop("checked", true);
        // $('#' + id).prop("checked", true);
    }

    // Set check or unchecked all checkboxes
    function checkAll(e, id) {
        // var checkboxes = document.getElementsByName('approval[]');

        // if (e.checked) {
        //   for (var i = 0; i < checkboxes.length; i++) {
        //     checkboxes[i].checked = true;
        //   }
        // } else {
        //   for (var i = 0; i < checkboxes.length; i++) {
        //     checkboxes[i].checked = false;
        //   }
        // }
        if (e.checked){
            $('[id=' + id + ']').prop("checked", true);
        }
        else{
            $('[id=' + id + ']').prop("checked", false);
        }

    }


</script>