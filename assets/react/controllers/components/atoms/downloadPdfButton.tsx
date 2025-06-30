const DownloadPdfButton = () => {
  const handleDownload = async () => {
    const cvElement = document.querySelector('.cv');
    if (!cvElement) {
      alert('La section CV est introuvable.');
      return;
    }

    const html = cvElement.outerHTML;

    try {
      const response = await fetch('/generate-cv-pdf', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ html }),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(errorText || 'Erreur serveur inconnue.');
      }

      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);

      const a = document.createElement('a');
      a.href = url;
      a.download = 'cv.pdf';
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);
    } catch (error: any) {
      console.error('Erreur lors du téléchargement :', error);
      alert(`Une erreur est survenue : ${error.message}`);
    }
  };

  return (
    <div className="mt-4 fixed top-8 left-1/2 transform -translate-x-1/2 z-50">
      <button
        onClick={handleDownload}
        className="bg-orange-300 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-orange-400 transition-colors duration-200 cursor-pointer"
      >
        Télécharger mon CV
      </button>
    </div>
  );
};

export default DownloadPdfButton;