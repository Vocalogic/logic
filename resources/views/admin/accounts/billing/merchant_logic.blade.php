@php
    $css = "
       #ccnumfield {
        background-color: #efefef !important;
      color: #bbae4e !important;
      border: 1px solid green !important;
      }
      #tokenform {
        input: padding: 15px;
        box-sizing: border-box;
        font-family: 'Nunito', sans-serif;
      }
      #tokenform input {
         padding-top: 15px !important;
      }
    ";
    if (currentMode() == 'dark')
        $css .= "#tokenform {
            color: #fff;
        }";
    $css = str_replace(" ", '', $css);
    $css = urlencode($css);
@endphp
<h5 class="card-title text-center">
    Update Credit Card
</h5>
<form name="tokenform" id="tokenform">
    <iframe id="tokenframe" name="tokenframe"
            src="https://{{env('APP_ENV') == 'local' ? "isv-uat" : "isv"}}.cardconnect.com/itoke/ajax-tokenizer.html?formatinput=true&usemonthnames=true&useexpiry=true&usecvv=true&css={{$css}}"
            scrolling="no" width="500" height="130" frameborder="0"></iframe>
    <input type="hidden" name="mytoken" id="mytoken"/>
    <a href="#" class="btn {{user()->account_id > 1 ? "bg-primary text-white" : "btn-primary"}}">Pre-Authorize Card</a>
</form>

@livewire('admin.logic-pay-component', ['account' => $account])


