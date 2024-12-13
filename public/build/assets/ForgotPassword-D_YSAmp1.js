import{T as m,f as l,b as s,u as o,a as e,t as u,h as b,k as p,l as f,F as x,o as i,Z as g}from"./app-CsOBiv3L.js";import{_ as n}from"./LogoText-B32ofg52.js";import{_,I as v}from"./TextInput-C6HJM62k.js";import{_ as w}from"./InputLabel-d2gH9LIP.js";import{b as h}from"./background-Bo4Ew-EA.js";import{_ as y}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./logo-BuEJ4M-s.js";const C={class:"min-h-screen flex bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]"},k={class:"w-full lg:w-1/2 flex items-center justify-center p-8"},F={class:"w-full max-w-md"},S={class:"text-center mb-8 lg:hidden"},V={class:"space-y-6 bg-white/60 backdrop-blur-sm p-8 rounded-xl shadow-xl"},E={key:0,class:"mb-4 font-medium text-sm text-green-600"},I=["disabled"],N={class:"hidden lg:flex lg:w-1/2 relative"},$={class:"absolute inset-0 bg-gradient-to-br from-[#101d0ebe] to-[#132a13d8]"},z={class:"relative z-10 flex flex-col justify-center items-center w-full p-12"},B={__name:"ForgotPassword",props:{status:String},setup(r){const a=m({email:""}),c=()=>{a.post(route("password.email"))};return(D,t)=>(i(),l(x,null,[s(o(g),{title:"Recuperar Contraseña"}),e("div",C,[e("div",k,[e("div",F,[e("div",S,[s(n,{colorClass:"text-[#4F772D]",textColorClass:"text-[#31572C]",class:"w-48 mx-auto"})]),e("div",V,[t[1]||(t[1]=e("h3",{class:"text-2xl font-bold text-[#31572C] mb-6"},"Recuperar Contraseña",-1)),t[2]||(t[2]=e("div",{class:"text-gray-600 text-sm mb-6"}," ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña. ",-1)),r.status?(i(),l("div",E,u(r.status),1)):b("",!0),e("form",{onSubmit:p(c,["prevent"]),class:"space-y-4"},[e("div",null,[s(w,{for:"email",value:"Correo electrónico"}),s(_,{id:"email",modelValue:o(a).email,"onUpdate:modelValue":t[0]||(t[0]=d=>o(a).email=d),type:"email",class:"mt-1 block w-full",required:"",autofocus:""},null,8,["modelValue"]),s(v,{class:"mt-2",message:o(a).errors.email},null,8,["message"])]),e("button",{type:"submit",class:"w-full bg-[#4F772D] text-white px-6 py-3 rounded-lg hover:bg-[#31572C] transition-colors",disabled:o(a).processing}," Enviar enlace de recuperación ",8,I)],32)])])]),e("div",N,[e("div",$,[e("div",{class:"absolute inset-0 mix-blend-overlay opacity-5 backdrop-blur-xl",style:f({backgroundImage:`url(${o(h)})`,backgroundSize:"cover",backgroundPosition:"center"})},null,4)]),e("div",z,[s(n,{colorClass:"text-white",textColorClass:"text-white",class:"w-64 mb-8"}),t[3]||(t[3]=e("h2",{class:"text-3xl font-bold text-white mb-4"},"¿Olvidaste tu contraseña?",-1)),t[4]||(t[4]=e("p",{class:"text-emerald-100 text-center max-w-md"}," Te enviaremos un enlace a tu correo electrónico para que puedas restablecer tu contraseña de forma segura. ",-1))])])])],64))}},M=y(B,[["__scopeId","data-v-500274fd"]]);export{M as default};
