import{T as c,f as p,b as t,u as a,a as s,k as f,p as g,w,l as b,F as x,o as v,Z as _,i as h,m as y}from"./app-CsOBiv3L.js";import{_ as n}from"./LogoText-B32ofg52.js";import{_ as r,I as i}from"./TextInput-C6HJM62k.js";import{_ as d}from"./InputLabel-d2gH9LIP.js";import{b as C}from"./background-Bo4Ew-EA.js";import{_ as V}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./logo-BuEJ4M-s.js";const k={class:"min-h-screen flex bg-gradient-to-br from-[#ECF39E] via-white to-[#90A955]"},F={class:"w-full lg:w-1/2 flex items-center justify-center p-8"},I={class:"w-full max-w-md"},R={class:"text-center mb-8 lg:hidden"},j={class:"mt-6"},z=["disabled"],N={class:"hidden lg:flex lg:w-1/2 relative"},q={class:"absolute inset-0 bg-gradient-to-br from-[#101d0ebe] to-[#132a13d8]"},B={class:"relative z-10 flex flex-col justify-center items-center w-full p-12"},E={__name:"Register",setup(S){const o=c({name:"",email:"",password:"",password_confirmation:"",terms:!1}),m=()=>{o.post(route("register"),{onFinish:()=>o.reset("password","password_confirmation")})};return(u,e)=>(v(),p(x,null,[t(a(_),{title:"Registro"}),s("div",k,[s("div",F,[s("div",I,[s("div",R,[t(n,{colorClass:"text-[#4F772D]",textColorClass:"text-[#31572C]",class:"w-48 mx-auto"})]),s("form",{onSubmit:f(m,["prevent"]),class:"space-y-6 bg-white/60 backdrop-blur-sm p-8 rounded-xl shadow-xl"},[e[5]||(e[5]=s("h3",{class:"text-2xl font-bold text-[#31572C] mb-6"},"Crear Cuenta",-1)),s("div",null,[t(d,{for:"name",value:"Nombre"}),t(r,{id:"name",modelValue:a(o).name,"onUpdate:modelValue":e[0]||(e[0]=l=>a(o).name=l),type:"text",class:"mt-1 block w-full",required:"",autofocus:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:a(o).errors.name},null,8,["message"])]),s("div",null,[t(d,{for:"email",value:"Correo electrónico"}),t(r,{id:"email",modelValue:a(o).email,"onUpdate:modelValue":e[1]||(e[1]=l=>a(o).email=l),type:"email",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:a(o).errors.email},null,8,["message"])]),s("div",null,[t(d,{for:"password",value:"Contraseña"}),t(r,{id:"password",modelValue:a(o).password,"onUpdate:modelValue":e[2]||(e[2]=l=>a(o).password=l),type:"password",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:a(o).errors.password},null,8,["message"])]),s("div",null,[t(d,{for:"password_confirmation",value:"Confirmar Contraseña"}),t(r,{id:"password_confirmation",modelValue:a(o).password_confirmation,"onUpdate:modelValue":e[3]||(e[3]=l=>a(o).password_confirmation=l),type:"password",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:a(o).errors.password_confirmation},null,8,["message"])]),e[6]||(e[6]=g('<div class="space-y-3" data-v-d60d4748><a href="/auth/google" class="w-full flex items-center justify-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-lg border hover:bg-gray-50 transition" data-v-d60d4748><img src="https://www.google.com/favicon.ico" class="w-5 h-5" alt="Google" data-v-d60d4748> Registrarse con Google </a><a href="/auth/github" class="w-full flex items-center justify-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition" data-v-d60d4748><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" data-v-d60d4748><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" data-v-d60d4748></path></svg> Registrarse con GitHub </a></div>',1)),s("div",j,[t(a(y),{href:u.route("login"),class:"text-sm text-[#4F772D] hover:text-[#31572C]"},{default:w(()=>e[4]||(e[4]=[h(" ¿Ya tienes una cuenta? Inicia sesión ")])),_:1},8,["href"])]),s("button",{type:"submit",class:"w-full bg-[#4F772D] text-white px-6 py-3 rounded-lg hover:bg-[#31572C] transition-colors",disabled:a(o).processing}," Registrarse ",8,z)],32)])]),s("div",N,[s("div",q,[s("div",{class:"absolute inset-0 mix-blend-overlay opacity-5 backdrop-blur-xl",style:b({backgroundImage:`url(${a(C)})`,backgroundSize:"cover",backgroundPosition:"center"})},null,4)]),s("div",B,[t(n,{colorClass:"text-white",textColorClass:"text-white",class:"w-64 mb-8"}),e[7]||(e[7]=s("h2",{class:"text-3xl font-bold text-white mb-4"},"¡Únete a nuestra comunidad!",-1)),e[8]||(e[8]=s("p",{class:"text-emerald-100 text-center max-w-md"}," Crea tu cuenta y comienza a participar en las mejores rifas con los premios más emocionantes. ",-1))])])])],64))}},H=V(E,[["__scopeId","data-v-d60d4748"]]);export{H as default};
