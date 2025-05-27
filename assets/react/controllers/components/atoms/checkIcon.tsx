const Check = require('../../../../icons/check.png');

import React from 'react';

const CheckIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={Check}
      alt="Check"
      className={`w-5 h-5`}
    />
  );
};

export default CheckIcon;