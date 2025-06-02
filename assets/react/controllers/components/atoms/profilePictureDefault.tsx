const ProfilePictureDefault = require('../../../../images/profilePictureDefault.png');

import React from 'react';

const ProfilePictureDefaultImage: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={ProfilePictureDefault}
      alt="ProfilePictureDefault"
      className={`w-20 h-20 rounded-full shadow`}
    />
  );
};

export default ProfilePictureDefaultImage;