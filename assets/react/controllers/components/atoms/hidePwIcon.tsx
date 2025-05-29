const HidePw = require('../../../../icons/hidePW.png');

import React from 'react';

const CheckIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={HidePw}
      alt="HidePw"
      className={`w-5 h-5`}
    />
  );
};

export default CheckIcon;