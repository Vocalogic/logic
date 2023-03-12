<style>{!! file_get_contents(public_path() . "/assets/oldbs/dist/css/bootstrap.css") !!}</style>


<style>
    #watermark {
        position: fixed;
        top: 45%;
        width: 100%;
        text-align: center;
        font-size: 120px;
        opacity: .08;
        transform: rotate(30deg);
        transform-origin: 50% 50%;
        z-index: -1000;
    }

    #watermark img {
        width: 100%;
    }

    .footer {
        position: fixed;
        width: 100%;
        bottom: 5px;
        padding-top: 25px;
        text-align: center;
        font-size: 10px;
    }

    .pagenum:before {
        content: counter(page);
        text-align: right;
        margin-left: 75px;

    }

    table {
        font-size: 12px;
    }

    h5 {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-weight: 800;
    }
    p {
        font-size: 12px;
    }

    .page-break {
        page-break-before: always;
    }


</style>

<html>

@if(setting('brandImage.watermark'))
    <div id="watermark"><img src="{{_file(setting('brandImage.watermark'))?->internal}}"></div>
@endif

@include('pdf.projects.summary')
<div class="page-break"></div>
@include('pdf.projects.category')


<div class="footer">
    {{setting('brand.name')}} Confidential - {{$project->name}} <span class='pagenum'></span>
</div>
</html>
