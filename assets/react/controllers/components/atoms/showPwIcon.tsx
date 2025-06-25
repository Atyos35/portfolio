const ShowPw = require('../../../../icons/showPW.png');

import React from 'react';

const CheckIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={ShowPw}
      alt="ShowPw"
      className={'cursor-pointer w-5 h-5'}
    />
  );
};

export default CheckIcon;