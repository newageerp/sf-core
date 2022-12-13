import React, { Fragment } from "react";
import {
  Template,
  useTemplatesLoader,
} from "@newageerp/v3.templates.templates-core";
import RsButton from "./RsButton";

interface Props {
  schema: string;
  children: Template[];
  defaultClick: "main" | "modal" | "new";
}

export default function RsButtonTemplate(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  return <RsButton {...props} elementId={element.id} />;
}
