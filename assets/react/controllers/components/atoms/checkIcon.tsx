const Check = require('../../../../icons/check.png');

import React from 'react';

const CheckIcon: React.FC<{ className?: string }> = ({ className = '' }) => {
  return (
    <img
      src={Check}
      alt="Check"
      className={`w-6 h-6 min-w-[1.25rem] min-h-[1.25rem] align-middle`}
    />
  );
};

export default CheckIcon;