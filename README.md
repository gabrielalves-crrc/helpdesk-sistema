Sistema HelpDesk CRRC Brasil
Sistema corporativo de gerenciamento de chamados e suporte técnico desenvolvido para a CRRC Brasil Equipamentos Ferroviários LTDA.

Visão Geral
O HelpDesk CRRC é uma plataforma completa para gestão de solicitações técnicas, suporte operacional e controle de materiais dentro da empresa. O sistema centraliza todas as demandas de assistência técnica, proporcionando rastreabilidade completa, redução de tempos de resposta e aumento da eficiência operacional.

Funcionalidades Principais
Gestão de Chamados
Abertura de novos chamados com categorização inteligente

Priorização automática por urgência e impacto

Atribuição às equipes técnicas especializadas

Acompanhamento em tempo real do status

Controle de SLA (Service Level Agreement)

Gestão de Usuários
Controle hierárquico de acessos (Admin, Técnico, Usuário)

Perfis customizados por departamento

Histórico completo de atividades por usuário

Autenticação segura com sessões controladas

Controle de Materiais
Solicitação de itens do almoxarifado

Rastreamento de materiais enviados

Gestão de estoque e patrimônio

Histórico de movimentações

Dashboard e Relatórios
Painel administrativo com métricas em tempo real

Gráficos de desempenho por equipe/departamento

Relatórios exportáveis (PDF, Excel, CSV)

KPIs de performance e produtividade

Recursos Avançados
Lixeira inteligente: Recuperação de itens excluídos (30 dias)

Sistema multilíngue: Suporte a 5 idiomas (PT, EN, ES, FR, ZH-CN)

Notificações: Alertas por e-mail e no sistema

Base de conhecimento: Soluções para problemas recorrentes

Mobile-responsive: Interface adaptada para todos dispositivos

Tecnologias Utilizadas
Backend
PHP 7.4+ - Lógica de negócio e processamento

MySQL 5.7+ - Banco de dados relacional

Apache/Nginx - Servidor web

Frontend
HTML5 - Estrutura semântica

CSS3 - Estilização moderna (Flexbox/Grid)

JavaScript (ES6+) - Interatividade e validações

Font Awesome 6 - Ícones vetoriais

Google Fonts - Tipografia moderna

Bibliotecas e Ferramentas
Google Translate API - Tradução automática

Font Awesome - Conjunto de ícones

Google Fonts - Fontes tipográficas

Responsive Design - Mobile-first approach




Estrutura do Projeto:
helpdesk-sistema/
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos principais
│   ├── js/
│   │   └── scripts.js         # JavaScript customizado
│   └── head/
│       └── head.php           # Template do cabeçalho
├── uploads/                   # Arquivos enviados
│   ├── profile_pics/          # Fotos de perfil
│   └── ticket_attachments/    # Anexos de chamados
├── includes/                  # Includes do sistema
│   ├── config.php            # Configurações do banco
│   ├── auth.php              # Controle de autenticação
│   └── functions.php         # Funções utilitárias
├── pages/                    # Páginas do sistema
│   ├── dashboard.php         # Painel principal
│   ├── novo-chamado.php      # Formulário de novo chamado
│   ├── meus-chamados.php     # Listagem de chamados
│   ├── itens-enviados.php    # Histórico de materiais
│   └── lixeira.php           # Itens excluídos
├── admin/                    # Área administrativa
│   ├── usuarios.php          # Gestão de usuários
│   ├── relatorios.php        # Relatórios avançados
│   └── configuracoes.php     # Configurações do sistema
├── index.php                 # Página inicial/login
├── logout.php                # Encerramento de sessão
├── database.sql              # Estrutura do banco de dados
└── README.md                 # Esta documentação




Níveis de Acesso
Administrador
Gerenciamento completo do sistema

Controle de todos os usuários

Configurações globais

Acesso a todos os relatórios

Backup e manutenção

Técnico
Visualizar e atender chamados atribuídos

Atualizar status dos chamados

Registrar soluções e procedimentos

Acesso a base de conhecimento

Usuário
Abrir novos chamados

Acompanhar próprios chamados

Visualizar histórico

Editar perfil pessoal

Instalação
Pré-requisitos
Servidor web (Apache/Nginx)

PHP 7.4 ou superior

MySQL 5.7 ou superior

Extensão PDO para MySQL habilitada
