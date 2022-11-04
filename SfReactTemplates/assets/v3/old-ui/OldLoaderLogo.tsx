import React from "react";

interface Props {
  size?: number;
}

export default function OldLoaderLogo(props: Props) {
  return (
    <img className={"animate-spin"} src={"https://stressfreesolutions.lt/static/images/NaeLogoSquare.png"} style={{ width: props.size }} />
  );
}
