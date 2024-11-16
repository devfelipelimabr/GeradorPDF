<?php

namespace App\Controllers;

use App\Models\PdfModel;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;

class PdfController extends BaseController
{
    private $session;
    private $pdfModel;
    private $dompdf;

    public function __construct()
    {
        $this->session = service('session');
        $this->pdfModel = new PdfModel();
        $this->dompdf = new Dompdf();
    }

    public function generate()
    {
        // Verifica se o usuário está autenticado
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuário não autenticado'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        // Recebe os dados para o PDF
        $data = $this->request->getJSON(true);

        // Gera o PDF e obtém o caminho
        $pdfPath = $this->generatePdf($data);

        // Salva o caminho e os dados no banco de dados
        $pdfId = $this->pdfModel->insert([
            'user_id' => $this->session->get('user_id'),
            'pdf_path' => $pdfPath,
            'data' => json_encode($data)
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'PDF gerado com sucesso', 'pdf_id' => $pdfId]);
    }

    public function download($pdf_id)
    {
        // Verifica se o usuário está autenticado
        if (!$this->session->has('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Usuário não autenticado'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $pdf = $this->pdfModel->find($pdf_id);

        // Verifica se o PDF existe e pertence ao usuário
        if (!$pdf || $pdf->user_id !== $this->session->get('user_id')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'PDF não encontrado ou acesso não autorizado'])
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN);
        }

        // Verifica se o arquivo PDF ainda existe no servidor
        if (!file_exists($pdf->pdf_path)) {
            // Recria o PDF usando o campo 'data' se o arquivo foi excluído
            $data = json_decode($pdf->data, true);
            $pdf->pdf_path = $this->generatePdf($data);

            // Atualiza o caminho do PDF no banco de dados
            $this->pdfModel->update($pdf_id, ['pdf_path' => $pdf->pdf_path]);
        }

        // Fornece o PDF para download
        return $this->response->download($pdf->pdf_path, null)
            ->setFileName(env('PROJECT_NAME') . '_' . time() . '.pdf');
    }

    /**
     * Função privada para geração de PDF.
     *
     * @param array $data Dados para popular o template do PDF.
     * @return string Caminho onde o PDF foi salvo.
     */
    private function generatePdf(array $data): string
    {
        // Cria o conteúdo HTML para o PDF
        $html = view('pdf_template', ['data' => $data]);

        // Gera o PDF com Dompdf
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        // Define o caminho para salvar o PDF
        $pdfPath = WRITEPATH . 'uploads/pdfs/' . uniqid(env('PROJECT_NAME')) . '.pdf';
        file_put_contents($pdfPath, $this->dompdf->output());

        return $pdfPath;
    }
}
