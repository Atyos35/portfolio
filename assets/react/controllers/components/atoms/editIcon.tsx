const EditImage = require('../../../../icons/editIcon.png');

import React from 'react';

const EditIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={EditImage}
      alt="EditImage"
      className={`w-5 h-5`}
    />
  );
};

export default EditIcon;