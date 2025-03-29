import React from "react";

interface MyComponentProps {
  fullName: string;
}

const MyComponent: React.FC<MyComponentProps> = ({ fullName }) => {
  return <div>{fullName}</div>;
};

export default MyComponent;
