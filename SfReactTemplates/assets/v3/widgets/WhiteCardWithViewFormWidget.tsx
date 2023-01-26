import React from "react";
import { WhiteCard } from "@newageerp/v3.bundles.widgets-bundle";
import { TextCardTitle } from "@newageerp/v3.bundles.typography-bundle";
import { Template } from '@newageerp/v3.templates.templates-core';
import {
  TemplatesParser,
  useTemplatesLoader,
} from "@newageerp/v3.templates.templates-core";
import { ToolbarButton } from "@newageerp/v3.bundles.buttons-bundle";
import { SFSOpenEditModalWindowProps } from "@newageerp/v3.bundles.popup-bundle";
import { filterScopes } from "../utils";
import { useRecoilValue } from '@newageerp/v3.templates.templates-core';

interface Props {
  title?: string;
  editId?: string;
  isCompact?: boolean;
  content: Template[];
  editScopes?: string[],
}

export default function WhiteCardWithViewFormWidget(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const {userState} = useTemplatesCore()

  const { title, editId } = props;
  const editData = (editId ? editId : "").split(":");

  const isShowEdit = filterScopes(
    tData.element,
    userState,
    props.editScopes
  );

  const onClick = () => {
    SFSOpenEditModalWindowProps({
      schema: editData[0],
      type: editData[1],
      id: tData.element.id,
    });
  };

  return (
    <WhiteCard isCompact={props.isCompact}>
      {((!!editId && isShowEdit) || !!title) && (
        <div className="tw3-flex tw3-gap-2">
          <TextCardTitle className={"tw3-flex-grow"}>{title}</TextCardTitle>
          {!!editId && isShowEdit && <ToolbarButton onClick={onClick} iconName="edit" />}
        </div>
      )}
      <TemplatesParser templates={props.content} />
    </WhiteCard>
  );
}
