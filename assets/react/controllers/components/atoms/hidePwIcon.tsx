const HidePw = require('../../../../icons/hidePW.png');

import React from 'react';

const HidePwIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={HidePw}
      alt="HidePw"
      className={`cursor-pointer w-5 h-5`}
    />
  );
};

export default HidePwIcon;