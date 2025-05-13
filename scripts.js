document.addEventListener('DOMContentLoaded'), () => {}
    const loginForm = document.getElementById('blkLogin');
    const forgotPasswordForm = document.getElementById('blkAlterarSenha');
    const usernameInput = document.getElementById('ttLogin');
    const passwordInput = document.getElementById('ttSenha');
    const forgotPasswordButton = document.getElementById('btEsqueceuSenha');
    const backButton = document.getElementById('btVoltar');
    const emailInput = document.getElementById('nmEmail');
    const successMessage = document.getElementById('blkSucessoEmail');
    const errorMessage = document.getElementById('blkFalhaEmail');

    // Auto preenchimento de login
    usernameInput.value = 'GESIO';
    passwordInput.value = 'GESIOCUNHA1';

    // Valida e envia o formulário de login
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();

        if (!username || !password) {
            showErrorMessage('Por favor, insira seu usuário e senha.');
            return;
        }

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            if (response.ok) {
                window.location.href = '/restricted';
            } else {
                const result = await response.json();
                showErrorMessage(result.message || 'Usuário ou senha incorretos.');
            }
        } catch (error) {
            showErrorMessage('Erro ao conectar ao servidor.');
        }
    });

    // Voltar ao formulário de login
    backButton.addEventListener('click', () => {
        forgotPasswordForm.style.display = 'none';
        loginForm.style.display = 'block';
    })

   //UPLOAD DE ARQUIVOS
   const fileInput = document.getElementById('fileInput');
const contractInput = document.getElementById('contractInput');
const fileList = document.getElementById('fileList');

const saveToLocal = () => {
  const links = Array.from(fileList.children).map(li => ({
    name: li.querySelector('a').textContent,
    href: li.querySelector('a').href
  }));
  localStorage.setItem('pdfFiles', JSON.stringify(links));
};

const loadFromLocal = () => {
  const data = JSON.parse(localStorage.getItem('pdfFiles') || '[]');
  data.forEach(({ name, href }) => {
    addListItem(name, href);
  });
};

const addListItem = (name, href) => {
  const li = document.createElement('li');
  const a = document.createElement('a');
  const del = document.createElement('button');

  a.href = href;
  a.target = '_blank';
  a.textContent = name;

  del.textContent = 'Excluir';
  del.onclick = () => {
    li.remove();
    saveToLocal();
  };

  li.appendChild(a);
  li.appendChild(del);
  fileList.appendChild(li);
};

const handleUpload = (fileInputElement, redirectUrl) => {
  const file = fileInputElement.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    addListItem(file.name, reader.result);
    saveToLocal();
    window.location.href = redirectUrl;
  };
  reader.readAsDataURL(file);
};

document.getElementById('uploadForm').addEventListener('submit', (e) => {
  e.preventDefault();
  handleUpload(fileInput, 'publicacoeslegais.html');
});

document.getElementById('contractForm').addEventListener('submit', (e) => {
  e.preventDefault();
  handleUpload(contractInput, 'contratos.html');
});

window.addEventListener('load', loadFromLocal);
