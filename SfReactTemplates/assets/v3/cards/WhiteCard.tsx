import React, {Fragment} from "react";
import { Template, TemplatesParser, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { WhiteCard as WhiteCardTpl } from "@newageerp/v3.bundles.widgets-bundle";
import { filterScopes } from "../utils";
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
  children: Template[];
  isCompact?: boolean,
  title?: string,

  scopes?: string[],
}

export default function WhiteCard(props: Props) {
  const { data: tData } = useTemplatesLoader();

  const userState = useRecoilValue(OpenApi.naeUserState);

  const isShow = filterScopes(
    tData.element,
    userState,
    props.scopes
  );

  if (!isShow) {
    return <Fragment />
  }

  return (
    <WhiteCardTpl isCompact={props.isCompact} title={props.title}>
      <TemplatesParser templates={props.children} />
    </WhiteCardTpl>
  );
}
