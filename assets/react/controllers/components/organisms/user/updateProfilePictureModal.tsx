import React, { useState } from 'react';
import Modal from '../../molecules/modal/modal';
import SaveButton from '../../atoms/saveButton';
import { FilePond, registerPlugin } from 'react-filepond'
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css'

registerPlugin(FilePondPluginImagePreview, FilePondPluginImageExifOrientation);

interface Props {
  isOpen: boolean;
  onClose: () => void;
  onUpload: (file: File, csrfToken: string) => void;
  csrfToken: string;
}

const UpdateProfilePictureModal: React.FC<Props> = ({ isOpen, onClose, onUpload, csrfToken }) => {
  const [files, setFiles] = useState<any[]>([]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (files.length > 0 && files[0].file) {
      onUpload(files[0].file, csrfToken);
      onClose();
    }
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title="Modifier la photo de profil">
      <form onSubmit={handleSubmit} className="space-y-4">
        <FilePond
          files={files}
          onupdatefiles={setFiles}
          name="files"
          labelIdle='Glissez des fichier ici ou <span class="filepond--label-action">Parcourir</span>'
        />
        <SaveButton />
      </form>
    </Modal>
  );
};

export default UpdateProfilePictureModal;