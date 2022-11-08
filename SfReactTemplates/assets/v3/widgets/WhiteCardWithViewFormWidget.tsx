import React from "react";
import { WhiteCard } from "@newageerp/v3.bundles.widgets-bundle";
import { TextCardTitle } from "@newageerp/v3.bundles.typography-bundle";
import TemplateLoader, { Template } from "../templates/TemplateLoader";
import {
  TemplatesParser,
  useTemplateLoader,
} from "../templates/TemplateLoader";
import { ToolbarButton } from "@newageerp/v3.bundles.buttons-bundle";
import { SFSOpenEditModalWindowProps } from "@newageerp/v3.popups.mvc-popup";

interface Props {
  title?: string;
  editId?: string;
  isCompact?: boolean;
  content: Template[];
}

export default function WhiteCardWithViewFormWidget(props: Props) {
  const { data: tData } = useTemplateLoader();

  const { title, editId } = props;
  const editData = (editId ? editId : "").split(":");

  const onClick = () => {
    SFSOpenEditModalWindowProps({
      schema: editData[0],
      type: editData[1],
      id: tData.element.id,
    });
  };

  return (
    <WhiteCard isCompact={props.isCompact}>
      {!!editId && !!title && (
        <div className="tw3-flex tw3-gap-2">
          <TextCardTitle className={"tw3-flex-grow"}>{title}</TextCardTitle>
          {!!editId && <ToolbarButton onClick={onClick} iconName="edit" />}
        </div>
      )}
      <TemplatesParser templates={props.content} />
    </WhiteCard>
  );
}
