import React from "react";
import { Template, TemplatesParser } from "../templates/TemplateLoader";
import { WhiteCard as WhiteCardTpl } from "@newageerp/v3.bundles.widgets-bundle";


interface Props {
  children: Template[];
  isCompact?: boolean,
  title?: string,
}

export default function WhiteCard(props: Props) {
  return (
    <WhiteCardTpl isCompact={props.isCompact} title={props.title}>
      <TemplatesParser templates={props.children} />
    </WhiteCardTpl>
  );
}
