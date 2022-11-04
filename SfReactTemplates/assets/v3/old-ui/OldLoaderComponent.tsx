import React from "react";
import Loader from "react-loader-spinner";

interface Props {
  size?: number;
}

export default function OldLoaderComponent(props: Props) {
  return (
    <div className={"flex p-8 items-center justify-center"}>
      <Loader
        type="Audio"
        color="#B3C0CE"
        height={props.size}
        width={props.size}
      />
    </div>
  );
}
