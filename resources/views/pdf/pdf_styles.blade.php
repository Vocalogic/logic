<style>

    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Regular.ttf);
    }


    @font-face {
        font-family: 'Nunito-Bold';
        font-style: normal;
        font-display: auto;
        src: url({{storage_path()}}/fonts/Nunito-Bold.ttf);
    }

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

    html {
        margin: 0px
    }

    @page {
        margin: 0px;
    }

    body {
        margin: 0px;
        font-family: 'Nunito', sans-serif;
        font-size: 12px;
    }

    .headline {
        font-family: 'Nunito-Bold', sans-serif;
        font-weight: 700;
        font-size: 16px;
    }

    .text {
        font-family: 'Nunito', sans-serif;
        font-size: 10px;
    }

    .bold {
        font-family: 'Nunito-Bold', sans-serif;
        font-size: 10px;
        font-weight: 700;
    }

    strong {
        font-family: 'Nunito-Bold', sans-serif;
        font-weight: 700;
    }

    .clear {
        clear: both;
    }


    .footer {
        position: fixed;
        left: 25%;
        bottom: 10px;
        padding-top: 25px;
        text-align: center;
        font-size: 10px;
    }

    .pagenum:before {
        content: counter(page);
        text-align: right;
        margin-left: 75px;

    }

    .twothirds {
        width: 66%;
    }

    .onethird {
        width: 33%;
    }

    .box {
        border: 1px #000000 solid;
    }

    .box .head {
        background: #06260e;
        color: #fff;
        padding: 5px;
        padding-left: 10px;
        font-family: Nunito-Bold;
        font-size: 14px;
    }

    .box .body {
        padding: 10px;
        font-family: Nunito;
    }

    #page {
        margin-left: 50px;
        margin-right: 50px;
    }
    .left {
        float: left;
    }

    .sigBlock {
        width : 100%;
        padding-left: 10px;
        padding-right: 10px;
    }

    .sigBlock .left {
        width : 40%;
        padding-left: 15px;
        height : 200px;
        float : left;
        margin-top: 10px;
        font-size: 14px;
    }

    .sigBlock .right {
        width : 60%;
        padding-left: 15px;
        height : 200px;
        float : left;
        margin-top: 10px;
        font-size: 14px;
    }



</style>

