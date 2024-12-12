import{Q as b,d as p,T as w,c as v,w as l,o as f,a as s,b as o,g,e as y,f as h,i as V,u as a}from"./app-C9Y4_UYl.js";import{_ as k,a as U}from"./AppLayout-C3rK1TvN.js";import{_ as d}from"./InputLabel-Crxm-Zxo.js";import{_ as m,a as u}from"./TextInput-vh2BdyE3.js";import{_ as E}from"./PrimaryButton-DtJoJnYB.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const $={class:"flex justify-between"},N={class:"py-12"},B={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},C={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},S={key:0,class:"fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md",role:"alert"},T={class:"mt-4 py-8"},j={class:"mt-4 py-2"},z={class:"flex justify-center"},F={__name:"editUser",setup(M){const x=b(),i=p(x.props.user),c=p(!1),t=w({name:i.value.name,email:i.value.email,password:""}),_=()=>{t.put(route("users.update",i.value.id),{preserveScroll:!0,onSuccess:()=>{c.value=!0,setTimeout(()=>{window.location.href=route("users.index")},3e3)},onError:n=>{console.error("Errores:",n)}})};return(n,e)=>(f(),v(k,{title:"Editar usuario"},{header:l(()=>[s("div",$,[e[4]||(e[4]=s("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Editar usuario ",-1)),o(U,{href:n.route("users.index"),class:"inline-flex items-center px-6 py-4 bg-indigo-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"},{default:l(()=>e[3]||(e[3]=[g(" Listar Usuarios ")])),_:1},8,["href"])])]),default:l(()=>[s("div",N,[s("div",B,[s("div",C,[e[7]||(e[7]=s("div",{class:"text-center py-4"},[s("h1",{class:"text-2xl font-semibold text-gray-800 leading-tight"}," Editar Usuario ")],-1)),c.value?(f(),y("div",S,e[5]||(e[5]=[s("strong",{class:"font-bold"},"¡Éxito!",-1),s("span",{class:"block sm:inline"}," Usuario actualizado correctamente.",-1),s("span",{class:"block text-sm"},"Redirigiendo en 3 segundos...",-1)]))):h("",!0),s("form",{class:"w-1/2 py-12 px-12 space-y-3",onSubmit:V(_,["prevent"])},[s("div",null,[o(d,{for:"name",value:""}),o(m,{id:"name",modelValue:a(t).name,"onUpdate:modelValue":e[0]||(e[0]=r=>a(t).name=r),type:"text",class:"mt-1 block w-full",placeholder:"Nombre"},null,8,["modelValue"]),o(u,{class:"mt-2",message:a(t).errors.name},null,8,["message"])]),s("div",T,[o(d,{for:"email",value:""}),o(m,{id:"email",modelValue:a(t).email,"onUpdate:modelValue":e[1]||(e[1]=r=>a(t).email=r),type:"email",class:"mt-1 block w-full",placeholder:"Correo"},null,8,["modelValue"]),o(u,{class:"mt-2",message:a(t).errors.email},null,8,["message"])]),s("div",j,[o(d,{for:"password",value:""}),o(m,{id:"password",modelValue:a(t).password,"onUpdate:modelValue":e[2]||(e[2]=r=>a(t).password=r),type:"password",class:"mt-1 block w-full",autfocus:"",placeholder:"Contraseña"},null,8,["modelValue"]),o(u,{class:"mt-2",message:a(t).errors.password},null,8,["message"])]),s("div",z,[o(E,{class:"mt-8",type:"submit"},{default:l(()=>e[6]||(e[6]=[g(" Actualizar contacto ")])),_:1})])],32)])])])]),_:1}))}};export{F as default};
