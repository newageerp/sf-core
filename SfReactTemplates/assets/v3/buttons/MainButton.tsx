import React from "react";

import {
  MainButton as MainButtonTpl,
  MainButtonProps,
} from "@newageerp/v3.buttons.main-button";


declare type Props = {
  
} & MainButtonProps;

export default function MainButton(props: Props) {
  return <MainButtonTpl
    {...props}
  />;
}
