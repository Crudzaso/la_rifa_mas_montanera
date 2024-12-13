import{g as d,T as x,f as r,b as o,u as t,w as m,F as n,o as s,Z as C,a as i,i as l,k as p,n as w,q as V}from"./app-CsOBiv3L.js";import{A as h}from"./AuthenticationCard-CUrOUata.js";import{_ as I}from"./AuthenticationCardLogo-C7QlsGWX.js";import{_,I as v}from"./TextInput-C6HJM62k.js";import{_ as g}from"./InputLabel-d2gH9LIP.js";import{_ as T}from"./PrimaryButton-D1UJ-2zR.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const U={class:"mb-4 text-sm text-gray-600"},$={key:0},B={key:1},F={class:"flex items-center justify-end mt-4"},M={__name:"TwoFactorChallenge",setup(N){const c=d(!1),e=x({code:"",recovery_code:""}),f=d(null),y=d(null),k=async()=>{c.value^=!0,await V(),c.value?(f.value.focus(),e.code=""):(y.value.focus(),e.recovery_code="")},b=()=>{e.post(route("two-factor.login"))};return(A,a)=>(s(),r(n,null,[o(t(C),{title:"Two-factor Confirmation"}),o(h,null,{logo:m(()=>[o(I)]),default:m(()=>[i("div",U,[c.value?(s(),r(n,{key:1},[l(" Please confirm access to your account by entering one of your emergency recovery codes. ")],64)):(s(),r(n,{key:0},[l(" Please confirm access to your account by entering the authentication code provided by your authenticator application. ")],64))]),i("form",{onSubmit:p(b,["prevent"])},[c.value?(s(),r("div",B,[o(g,{for:"recovery_code",value:"Recovery Code"}),o(_,{id:"recovery_code",ref_key:"recoveryCodeInput",ref:f,modelValue:t(e).recovery_code,"onUpdate:modelValue":a[1]||(a[1]=u=>t(e).recovery_code=u),type:"text",class:"mt-1 block w-full",autocomplete:"one-time-code"},null,8,["modelValue"]),o(v,{class:"mt-2",message:t(e).errors.recovery_code},null,8,["message"])])):(s(),r("div",$,[o(g,{for:"code",value:"Code"}),o(_,{id:"code",ref_key:"codeInput",ref:y,modelValue:t(e).code,"onUpdate:modelValue":a[0]||(a[0]=u=>t(e).code=u),type:"text",inputmode:"numeric",class:"mt-1 block w-full",autofocus:"",autocomplete:"one-time-code"},null,8,["modelValue"]),o(v,{class:"mt-2",message:t(e).errors.code},null,8,["message"])])),i("div",F,[i("button",{type:"button",class:"text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer",onClick:p(k,["prevent"])},[c.value?(s(),r(n,{key:1},[l(" Use an authentication code ")],64)):(s(),r(n,{key:0},[l(" Use a recovery code ")],64))]),o(T,{class:w(["ms-4",{"opacity-25":t(e).processing}]),disabled:t(e).processing},{default:m(()=>a[2]||(a[2]=[l(" Log in ")])),_:1},8,["class","disabled"])])],32)]),_:1})],64))}};export{M as default};
