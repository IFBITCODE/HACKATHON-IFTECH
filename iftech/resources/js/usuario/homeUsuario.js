document.addEventListener("DOMContentLoaded",()=>{

const searchForm=document.getElementById("searchForm");
const searchInput=document.getElementById("searchInput");
const searchButton=document.getElementById("searchButton");
const searchResult=document.getElementById("searchResult");
const resultTag=document.getElementById("resultTag");
const resultBody=document.getElementById("resultBody");

if(searchForm&&searchInput){
searchForm.addEventListener("submit",async e=>{
e.preventDefault();

const mensagem=searchInput.value.trim();
if(!mensagem)return;

searchButton.disabled=true;
searchButton.innerHTML='<i class="fa-solid fa-circle-notch fa-spin"></i>';
searchResult.classList.add("active");
resultTag.textContent="Consultando Guia...";
resultBody.innerHTML='<div class="loading-state"><i class="fa-solid fa-sparkles"></i> Buscando os melhores locais para você...</div>';

const tokenMeta=document.querySelector('meta[name="csrf-token"]');
const token=tokenMeta?tokenMeta.getAttribute("content"):"";

try{
const response=await fetch(searchForm.action,{
method:"POST",
headers:{
"Content-Type":"application/json",
"X-CSRF-TOKEN":token,
"Accept":"application/json",
"X-Requested-With":"XMLHttpRequest"
},
body:JSON.stringify({mensagem})
});

const data=await response.json();

if(response.ok&&data.resposta){
resultTag.textContent="Sugestões de Turismo";

let textoFormatado=data.resposta
.replace(/\n/g,"<br>")
.replace(/(se voce for nesse que é nosso parceiro voce pode ganhar algumas moedas de troca)/gi,'<span class="badge-parceiro"><i class="fa-solid fa-coins"></i> $1</span>');

resultBody.innerHTML=textoFormatado;
}else{
resultTag.textContent="Aviso do Sistema";
resultBody.innerHTML=`<div class="error-msg">${data.resposta||data.erro||"Não foi possível obter resposta no momento."}</div>`;
}

}catch(error){
console.error("Erro na busca:",error);
resultTag.textContent="Erro de Conexão";
resultBody.innerHTML='<div class="error-msg">Ocorreu um erro ao conectar ao servidor. Tente novamente.</div>';
}finally{
searchButton.disabled=false;
searchButton.innerHTML='<i class="fa-solid fa-magnifying-glass"></i>';
searchResult.scrollIntoView({behavior:"smooth",block:"nearest"});
}
});
}

const loginBtn=document.getElementById("openLogin");
const loginModal=document.getElementById("loginModal");
const closeLogin=document.getElementById("closeLogin");
const fakeLoginForm=document.getElementById("fakeLoginForm");
const googleLogin=document.getElementById("googleLogin");
const registerFake=document.getElementById("registerFake");
const loginMessage=document.getElementById("loginMessage");

const coinsBtn=document.getElementById("coinsToggle");
const sidebar=document.getElementById("coinsSidebar");
const closeSidebar=document.getElementById("closeSidebar");
const sidebarOverlay=document.getElementById("sidebarOverlay");
const coinsValue=document.getElementById("coinsValue");
const codeForm=document.getElementById("codeForm");
const codeInput=document.getElementById("codeInput");
const codeMessage=document.getElementById("codeMessage");

const codes={
"TURISMO100":100,
"ROTAPB50":50,
"GUIA25":25
};

function getUser(){
try{
return JSON.parse(localStorage.getItem("fakeUser"));
}catch{
return null;
}
}

function saveUser(user){
localStorage.setItem("fakeUser",JSON.stringify(user));
}

function updateInterface(){
const user=getUser();

if(user){
loginBtn.textContent="Sair";
coinsBtn.style.display="flex";
coinsBtn.style.alignItems="center";
coinsBtn.style.justifyContent="center";
coinsValue.textContent=user.moedas||0;
}else{
loginBtn.textContent="Entrar";
coinsBtn.style.display="none";
coinsValue.textContent="0";
sidebar.classList.remove("active");
sidebarOverlay.classList.remove("active");
}
}

loginBtn.addEventListener("click",e=>{
e.preventDefault();

const user=getUser();

if(user){
localStorage.removeItem("fakeUser");
updateInterface();
return;
}

loginModal.classList.add("active");
});

closeLogin.addEventListener("click",()=>{
loginModal.classList.remove("active");
loginMessage.textContent="";
});

loginModal.addEventListener("click",e=>{
if(e.target===loginModal){
loginModal.classList.remove("active");
loginMessage.textContent="";
}
});

fakeLoginForm.addEventListener("submit",e=>{
e.preventDefault();

const email=document.getElementById("loginEmail").value.trim();
const password=document.getElementById("loginPassword").value.trim();

if(!email||!password)return;

saveUser({
nome:"Usuário Teste",
email:email,
moedas:25
});

loginModal.classList.remove("active");
fakeLoginForm.reset();
updateInterface();
});

googleLogin.addEventListener("click",()=>{
saveUser({
nome:"Usuário Google",
email:"google@teste.com",
moedas:25
});

loginModal.classList.remove("active");
updateInterface();
});

registerFake.addEventListener("click",e=>{
e.preventDefault();

const email=document.getElementById("loginEmail").value.trim();

if(!email){
loginMessage.textContent="EM DESENVOLVIMENTO";
return;
}

saveUser({
nome:"Novo Usuário",
email:email,
moedas:10
});

loginModal.classList.remove("active");
fakeLoginForm.reset();
updateInterface();
});

coinsBtn.addEventListener("click",()=>{
if(!getUser())return;
sidebar.classList.add("active");
sidebarOverlay.classList.add("active");
});

closeSidebar.addEventListener("click",()=>{
sidebar.classList.remove("active");
sidebarOverlay.classList.remove("active");
});

sidebarOverlay.addEventListener("click",()=>{
sidebar.classList.remove("active");
sidebarOverlay.classList.remove("active");
});

codeForm.addEventListener("submit",e=>{
e.preventDefault();

const user=getUser();

if(!user){
codeMessage.textContent="Você precisa estar logado.";
codeMessage.className="code-error";
return;
}

const codigo=codeInput.value.trim().toUpperCase();

if(!codigo){
codeMessage.textContent="Digite um código.";
codeMessage.className="code-error";
return;
}

if(!codes[codigo]){
codeMessage.textContent="Código inválido ou já utilizado.";
codeMessage.className="code-error";
return;
}

const moedas=codes[codigo];

user.moedas=(user.moedas||0)+moedas;

saveUser(user);

coinsValue.textContent=user.moedas;

codeMessage.textContent=`Código aplicado! +${moedas} moedas.`;
codeMessage.className="code-success";

codeInput.value="";

delete codes[codigo];
});

updateInterface();

});