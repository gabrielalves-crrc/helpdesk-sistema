<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">



 <!-- Título (com valor padrão caso não seja definido) -->
    <title><?php echo isset($pageTitle) ? $pageTitle : 'HelpDesk CRRC'; ?></title>
    
    <!-- Description (com valor padrão) -->
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Sistema de HelpDesk da CRRC Brasil'; ?>">
    
    <!-- Keywords (com valor padrão) -->
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'helpdesk, suporte técnico, CRRC, chamados'; ?>">

    <!-- Autor -->
    <meta name="author" content="CRRC BRASIL EQUIPAMENTOS FERROVIÁRIOS LTDA">
    <meta name="theme-color" content="#ffffff">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <!-- <meta property="og:url" content=""> -->
    <meta property="og:title" content="Sistema de HelpDesk da CRRC Brasil">
    <meta property="og:description" content="O Sistema de HelpDesk da CRRC Brasil é uma plataforma corporativa desenvolvida para gerenciamento integrado de suporte técnico e operacional da CRRC Brasil Equipamentos Ferroviários LTDA. Trata-se de uma solução personalizada que centraliza todas as demandas de assistência técnica, manutenção, solicitações de materiais e suporte aos colaboradores da empresa.">
    <meta property="og:image" content="uploads/logotipo-icone.png">


    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <!-- <meta property="twitter:url" content="https://seusite.com/"> -->
    <meta property="twitter:title" content="Nome da Sua Página | Descrição Breve">
    <meta property="twitter:description" content="O Sistema de HelpDesk da CRRC Brasil é uma plataforma corporativa desenvolvida para gerenciamento integrado de suporte técnico e operacional da CRRC Brasil Equipamentos Ferroviários LTDA. Trata-se de uma solução personalizada que centraliza todas as demandas de assistência técnica, manutenção, solicitações de materiais e suporte aos colaboradores da empresa.">
    <meta property="twitter:image" content="uploads/logotipo-icone.png">


    <!-- Favicon (versão moderna - múltiplos formatos) -->
    <link rel="icon" type="image/x-icon" href="uploads/logotipo-icone.png">
    <link rel="icon" type="image/png" sizes="16x16" href="uploads/logotipo-icone.png">
    <link rel="icon" type="image/png" sizes="32x32" href="uploads/logotipo-icone.png">
    <link rel="apple-touch-icon" sizes="180x180" href="uploads/logotipo-icone.png">
    <!-- <link rel="manifest" href="/site.webmanifest"> -->

    <!-- <link rel="canonical" href="https://seusite.com/"> -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'pt',
                includedLanguages: 'pt,en,zh-CN,es,fr',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>