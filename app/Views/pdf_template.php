<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Documento PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content-section {
            margin: 20px 0;
        }

        .content-section p {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Cabeçalho do Documento -->
        <div class="header">
            <h1>Relatório de Dados</h1>
            <p>Gerado em: <?= date('d/m/Y') ?></p>
        </div>

        <!-- Seção de Conteúdo -->
        <div class="content-section">
            <h2>Informações Gerais</h2>
            <p><strong>Nome:</strong> <?= esc($data['nome']) ?? 'Não informado' ?></p>
            <p><strong>Endereço:</strong> <?= esc($data['endereco']) ?? 'Não informado' ?></p>
            <p><strong>Data de Nascimento:</strong> <?= esc($data['data_nascimento']) ?? 'Não informado' ?></p>
        </div>

        <!-- Tabela de Detalhes (se aplicável) -->
        <?php if (isset($data['detalhes']) && is_array($data['detalhes'])): ?>
            <h2>Detalhes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['detalhes'] as $detalhe): ?>
                        <tr>
                            <td><?= esc($detalhe['item']) ?></td>
                            <td><?= esc($detalhe['descricao']) ?></td>
                            <td><?= esc($detalhe['quantidade']) ?></td>
                            <td><?= esc($detalhe['valor']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Rodapé do Documento -->
        <div class="footer">
            <p>Este é um documento gerado automaticamente. Para mais informações, entre em contato.</p>
        </div>
    </div>

</body>

</html>