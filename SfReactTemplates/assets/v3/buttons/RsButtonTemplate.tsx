import React, { Fragment } from "react";
import {
  Template,
  useTemplateLoader,
} from "../templates/TemplateLoader";
import RsButton from "./RsButton";

interface Props {
  schema: string;
  children: Template[];
  defaultClick: "main" | "modal" | "new";
}

export default function RsButtonTemplate(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  return <RsButton {...props} elementId={element.id} />;
}
