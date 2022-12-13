import React from "react";
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core';
import { TabContainer as TabContainerImpl } from "@newageerp/ui.ui-bundle";

type Props = {
  items: {
    title: string;
    content: Template[];
  }[];
  title?: string;
};

export default function TabContainer(props: Props) {
  return (
    <TabContainerImpl
      title={props.title ? { text: props.title } : undefined}
      items={props.items.map((t) => {
        return {
          content: <TemplatesParser templates={t.content} />,
          tab: {
            children: t.title,
          },
        };
      })}
    />
  );
}
